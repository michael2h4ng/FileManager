(($) ->

    # Custom single/double click listener
    # Reference: http://stackoverflow.com/questions/6330431/jquery-bind-double-click-and-single-click-separately
    $.fn.single_double_click = (single_click_callback, double_click_callback, timeout) ->
        this.each ->
            clicks = 0
            self = this
            $(this).click (event)->
                clicks++;
                if clicks is 1
                    setTimeout ->
                        if clicks is 1
                            single_click_callback.call(self, event)
                        else
                            double_click_callback.call(self, event)
                        clicks = 0;
                    , (timeout || 250)

    init = ->
        # Set up CSRF Token for every AJAX request
        $.ajaxSetup
            headers:
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")

        $("#new_folder").on "click", ->
            $(this).prop("disabled", true)
            insertObject("directory", true)
            names = []

            $(".object").each ->
                basename = $(this).data("basename")

                if basename.indexOf("Untitled Folder") > -1
                    basename = basename.replace("Untitled Folder", "").trim()
                    if not basename
                        basename = 1
                    names.push(parseInt(basename,10))

            if names.length > 0
                newFolder = "Untitled Folder #{Math.max.apply(Math, names) + 1}"
            else
                newFolder = "Untitled Folder"

            $("#input-folder").val("#{newFolder}").focus().select() # Increment and populate the new Untitled Folder

        $("#file_system").on "focusout", ".object .name #input-folder", ->
            $(this).parent().submit()

        $("#file_system").on "submit", "#create-form", (e) ->
            e.preventDefault()

            if not $("#input-folder").val()
                $(this).parents("object-container").remove()
                $("#new_folder").prop("disabled", false)
                return false

            currentObject = $(this).parents(".object")
            currentObject.first().addClass("uploading") # Add uploading animation

            $.ajax
                url: "/manager/put/directory",
                data:
                    path: $("#file_system").data("dirpath")
                    dirName: $("#input-folder").val()
                type: "PUT"
                dataType: "json"
            .success (response) ->
                # Remove form and populate meta data
                populateMeta(currentObject, "directory", response)
                initFileSelection(currentObject)
            .fail (response) ->
                # Show error message
                alert("Failed to create the folder")
                $(this).parents(".object").removeClass("uploading")
                .find("input-folder").focus().select()
            .done (response) ->
                $("#new_folder").prop("disabled", false)

        $('[data-toggle="tooltip"]').tooltip()
        breadcrumb()
        uploader()
        initRename()
        initFileSelection()
        initDeletion()

    breadcrumb = ->
        fullPath = $("#file_system").data("dirpath")

        if fullPath is '/'
            return false

        currentLink = "/home"
        $.each fullPath.split('/'), (index, node) ->
            currentLink += "/#{node}"
            $(".breadcrumb").append("""<li class="breadcrumb-item"><a href="#{currentLink}">#{node}</a></li>""")

    uploader = ->
        $("#fileupload").on "change", ->
            files = this.files
            maxFileSize = $("#file_system").data("maxfilesize") - 512 # Consider POST overhead

            $.each files, (index, file) ->
                if file.size > maxFileSize
                    alert "File #{file.name} is too large"
                    return false

            formData = new FormData($("#upload")[0])

            $.ajax
                url: "/manager/put/file",
                type: "POST"
                dataType: "json"
                data: formData
                cache: false
                contentType: false # Prevent jQuery from processing the data
                processData: false
            .success (responses) ->
                $(responses.errors).each (index, error) ->
                    console.log error
                $(responses.success).each (index, file) ->
                    newObject = $(insertObject("file", false)).children()
                    populateMeta(newObject, "file", file)
                    initFileSelection(newObject)
            .fail ->
                alert("Upload failed")

    insertObject = (objectType, newObject) ->
        nameInput = """
            <form id="create-form">
                <label for="input-folder" class="sr-only">New folder</label>
                <input type="text" placeholder="Folder Name" id="input-folder" class="text-center">
                <input type="hidden" name="_method" value="PUT">
            </form>
            """

        if newObject
            name = nameInput
        else
            name = null

        objectModel = """
             <div class="col-xs-4 col-sm-3 col-md-2 object-container">
                 <div data-basename="" data-mime="" data-filetype="" class="object object-new">
                     <div class="icon-container">
                         <div class="icon-base #{objectType}"></div>
                         <div class="icon-main"></div>
                     </div>
                     <div class="name-container">
                         <div title="" class="name text-primary" role="button">
                            #{name}
                         </div>
                         <div class="meta-info text-muted"></div>
                     </div>
                 </div>
             </div>
             """

        if objectType is "directory"
            return $(objectModel).prependTo( "#file_system" )
        else if objectType is "file"
            return $(objectModel).appendTo( "#file_system" )

    populateMeta = (object, objectType, objectMeta) ->
        if not objectMeta.pathinfo.dirname
            link = "/home#{objectMeta.path}"
        else
            link = "/home/#{objectMeta.path}"

        object.removeClass("uploading").removeClass("renaming").removeClass("object-new") # Remove uploading animation
        .data("filetype", objectMeta.mime)
        .data("basename", objectMeta.pathinfo.basename)
        .data("fullpath", objectMeta.path)
        .find(".name").empty() # Replace name class with new data
        .append("""<a class="link" href="#{link}">#{objectMeta.pathinfo.basename}</a> <a href="#" class="hide rename"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></a>""")
        .parent().children(".meta-info").empty() # Replace last modified time with "Just now"
        .append("<div class=\"meta-info text-muted\">Just now</div>")

    initRename = () ->
        $("#file_system").on "click", ".object .rename", (e) ->
            e.preventDefault()
            renameInput($(this).parents(".object"))

        $("#file_system").on "focusout", ".object #input-file", ->
            $(this).parent().submit()
            return false

        $("#file_system").on "submit", ".object #rename-form", (e) ->
            e.preventDefault()

            currentObject = $(this).parents(".object")
            oldName = currentObject.data("basename")
            newName = $(this).children("#input-file").val()
            fileType = currentObject.data("filetype")

            if not newName or (oldName is newName) # No name change
                $(this).parent().find("a").show()
                $(this).remove()
                return false

            currentObject.addClass("renaming") # Add renaming effects

            $.ajax
                url: "/manager/move",
                data:
                    path: $("#file_system").data("dirpath")
                    oldName: oldName
                    newName: newName
                    fileType: fileType
                type: "POST"
                dataType: "json"
            .success (response) ->
                # Remove form and populate meta data
                currentObject.find(".name").empty()
                populateMeta(currentObject, fileType, response)
            .fail ->
                # Show error message
                alert("Failed to rename the object")
                currentObject.removeClass("renaming").find(".name a").show()
                currentObject.find("#rename-form").remove()

    renameInput = (object) ->
        oldName = object.data("basename");
        nameInput = """
            <form id="rename-form">
                <label for="input-file" class="sr-only">File Name</label>
                <input type="text" placeholder="Folder Name" id="input-file" class="text-center" value="#{oldName}">
            </form>
            """

        object.find(".name a").hide()
        $(nameInput).prependTo(object.find(".name")).find("#input-file").focus().select()

    initFileSelection = (object) ->
        if not object
            object = $("#file_system .object")

        object.single_double_click(
            (() ->
                $(this).toggleClass("selected")),
            (() ->
                if ($(this).data("filetype") is "directory")
                    $("#file_system").addClass("loading")
                window.location = ($(this).find(".link").attr("href"))))

    initDeletion = () ->
        $("#delete_object").on "click", ->
            selectedFiles = $(".selected")

            $(this).prop("disabled", true)

            selectedFiles.each (index, file) ->
                fileType = $(file).data("filetype")
                $.ajax
                    url: "/manager/delete",
                    data:
                        path: $(file).data("fullpath")
                        fileType: fileType
                    type: "DELETE"
                    dataType: "json"
                .success ->
                    # Remove form and populate meta data
                    $(file).fadeOut 800, ->
                        $(this).parent().remove()
                .fail ->
                    # Show error message
                    alert("""Failed to delete #{fileType} #{$(file).data("basename")}""")

            $("#delete_object").prop("disabled", false) # Enable delete button

    return init()

) jQuery
