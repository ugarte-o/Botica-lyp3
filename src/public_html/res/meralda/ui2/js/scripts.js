/*!
    * Start Bootstrap - SB Admin v7.0.7 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2023 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
    */
    // 
// Scripts
// 


// Restore sidebar state immediately (before paint) to avoid flash
if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
    document.body.classList.add('sb-sidenav-toggled');
}

$(function(){
    $('#sidebarToggle').click(function(){
        $("body").toggleClass("sb-sidenav-toggled");
        localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
    });
});



