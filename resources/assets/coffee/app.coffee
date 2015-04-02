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
                            </form>
                        </div>
                         <div class="meta-info text-muted"></div>
                     </div>
                 </div>
             </div>
             """

    init = ->
        $("#new_folder").on "click", ->
            $(this).attr("disabled", true)
            $("#file_system").prepend(folder)
            names = []
            $(".object").each ->
                basename = $(this).data("basename")

                if basename.indexOf("Untitled Folder") > -1
                    basename = basename.replace("Untitled Folder", "").trim()
                    if not basename
                        basename = 1
                    names.push(parseInt(basename,10));

            next = Math.max.apply(Math, names) + 1 # Find the max number of all "Untitled Folder"s
            $("#input-folder").focus().attr("value", "Untitled Folder #{next}") # Increment and populate the new Untitled Folder

    return init()
) jQuery
