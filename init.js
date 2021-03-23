function process(path, renamefield, renamepostfix) {
    var form = document.getElementById('content-edit'),
        renamefield = typeof renamefield != "undefined" ? '&renamefield=' + renamefield : '',
        renamepostfix = typeof renamepostfix != "undefined" ? '&renamepostfix=' + renamepostfix : '';
    
    querystring = form.action.split('?')[1];

    // form.target = '_blank';
    form.action = path + '?' + querystring + renamefield + renamepostfix;
}
document.querySelector('.frwssr_cloneitem__button').onclick = function() {
    process(this.dataset.path, this.dataset.renamefield, this.dataset.renamepostfix);
};
