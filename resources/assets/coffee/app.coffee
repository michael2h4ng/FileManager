(($) ->
	init = ->
		$("#file_system").on "click", ".object", ->
			$("#file_system > div > .object").removeClass "selected"
			$(this).addClass "selected"

	return init()
) jQuery
