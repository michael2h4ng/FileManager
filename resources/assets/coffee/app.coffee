(($) ->

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

        $("#file_system").on "submit", ".object .name form", (e) ->
            e.preventDefault()

            if not $("#input-folder").val()
                $("#file_system .object").first().parent().remove()
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
            .fail (response) ->
                # Show error message
                alert("Failed to create the folder")
                $("#file_system .object").first().removeClass("uploading")
                .find("input-folder").focus().select()
            .done (response) ->
                $("#new_folder").prop("disabled", false)

        $('[data-toggle="tooltip"]').tooltip()
        breadcrumb()
        uploader()

    breadcrumb = ->
        fullPath = $("#file_system").data("dirpath")

        if fullPath is '/'
            return false

        currentLink = "/home"
        $.each fullPath.split('/'), (index, node) ->
            currentLink += "/#{node}"
            $(".breadcrumb").append("<li class=\"breadcrumb-item\"><a href=\"" + currentLink + "\">#{node}</a></li>")

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
                    console.log file
                    newObject = $(insertObject("file", false)).children()
                    populateMeta(newObject, "file", file)
            .fail ->
                alert("Upload failed")

    insertObject = (objectType, newObject) ->
        nameInput = """
            <form>
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
             <div class="col-xs-4 col-sm-3 col-md-2">
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
        object.removeClass("uploading").removeClass("object-new") # Remove uploading animation
        .data("filetype", objectMeta.mime)
        .data("basename", objectMeta.pathinfo.basename)
        .data("fullpath", objectMeta.path)
        .find(".name").empty() # Replace name class with new data
        .append("<a href=\"/home" + objectMeta.path + "\">#{objectMeta.pathinfo.basename}</a>")
        .append("<div class=\"meta-info text-muted\">Just now</div>")

    return init()

) jQuery
