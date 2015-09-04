UtilityModal
============

Methods
-------


### create

UtilityModal.create(options)

Create a modal window.

1. Creates a modal window and displays it

2. Then the window is hidden, it will be destroyed automatically

3. Accepts `options` argument, which may contain following values:

 - title - window title

 - class - window class; default: "modal-full"

 - contents - the contents of a modal window; default: "loading..."

 - ajax - ajax request options (http://api.jquery.com/jquery.ajax/)

 - callback - callback for call, if ajax-request uses; callback accepts an
 argument modal window html-element (<div class="modal">...</div>)

4. Result: modal window html-element (<div class="modal">...</div>)

5. For correct display, you must connect Asset UtilityModalAsset