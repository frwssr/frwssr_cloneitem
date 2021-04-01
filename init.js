function process(path, renamefield, renamepostfix, unsetfields) {
    var form = document.getElementById('content-edit'),
        renamefield = typeof renamefield != "undefined" ? '&renamefield=' + renamefield : '',
        renamepostfix = typeof renamepostfix != "undefined" ? '&renamepostfix=' + renamepostfix : '';
        unsetfields = typeof unsetfields != "undefined" ? '&unsetfields=' + unsetfields : '';
    
    querystring = form.action.split('?')[1];

    form.action = path + '?' + querystring + renamefield + renamepostfix + unsetfields;
    form.submit();
}
document.querySelector('.frwssr_cloneitem__button').onclick = function() {
    process(this.dataset.path, this.dataset.renamefield, this.dataset.renamepostfix, this.dataset.unsetfields);
    return false;
};
