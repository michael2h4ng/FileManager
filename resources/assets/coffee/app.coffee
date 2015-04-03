(($) ->

    folder = """
             <div class="col-xs-4 col-sm-3 col-md-2">
                 <div data-basename="" data-mime="" data-filetype="" class="object new-folder">
                     <div class="icon-container">
                         <div class="icon-base directory"></div>
                         <div class="icon-main"></div>
                     </div>
                     <div class="name-container">
                         <div title="" class="name text-primary" role="button">
                            <form>
                                <label for="input-folder" class="sr-only">New folder</label>
                                <input type="text" placeholder="Folder Name" id="input-folder" class="text-center">
                                <input type="hidden" name="_method" value="PUT">
                            </form>
                        </div>
                         <div class="meta-info text-muted"></div>
                     </div>
                 </div>
             </div>
             """

    init = ->
        $.ajaxSetup
            headers:
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")

        $("#new_folder").on "click", ->
            $(this).prop("disabled", true)
            $("#file_system").prepend(folder)
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

            $("#file_system .object").first().addClass("uploading") # Add uploading animation

            $.ajax
                url: "/manager/put/directory",
                data:
                    path: $("#file_system").data("dirpath")
                    dirName: $("#input-folder").val()
                type: "PUT"
                dataType: "json"
            .success (response) ->
                # Remove form and populate meta data
                $("#file_system .object").first().removeClass("uploading").removeClass("new-folder") # Remove uploading animation
                .attr("data-filetype", response.mime)
                .attr("data-fullpath", response.path)
                .attr("data-basename", response.pathinfo.basename)
                .data("basename", response.pathinfo.basename)
                .find(".name").empty() # Remove form
                .append("<a href=\"/home/" + response.path + "\">#{response.pathinfo.basename}</a>") # Populate meta data
                .append("<div class=\"meta-info text-muted\">Just now</div>")
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
        $("#fileupload").fileupload ->
            type: "PUT",
            dataType: 'json'
            progressall: (e, data) ->
                progress = parseInt(data.loaded / data.total * 100, 10)
                $('#progress .progress-bar').css('width', progress + '%')
            done: (e, data) ->
                $.each data.result.files, (index, file) ->
                $('<p/>').text(file.name).appendTo($("#file_system"))

    return init()

) jQuery
