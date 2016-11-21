var icons = ["icon-user", "icon-academic", "icon-students", "icon-faculty", "icon-viewing", "icon-admission", "icon-reports", "icon-announcements"];

function showNav() {
    $.ajax({
        url: "template/data/navbar.json?ts=" + ((new Date()).getTime()),
        type: "GET",
        dataType: "json",
        success: function (data) {
            for (var obj in data) {
                seed($("#page-nav ul:first-child"), data[obj], icons[obj % icons.length]);
            }
        },
        error: function (err) { }
    });
}

function seed(parent, obj, icon) {
    var head = $("<li />").addClass("menu-item").addClass("inline").addClass("dropdown");
    var link = $("<a />").attr("href", "#").addClass("dropdown");
    addClick(link, obj);
    link.append($("<div />").addClass("icon").addClass(icon));
    link.append(obj.linktext.toUpperCase());
    head.append(link);
    if (obj.sub.length > 0) {
        var child = $("<ul />").addClass("dropdown-menu").attr("role", "menu").attr("aria-labelledby", "dropdownMenu");
        innerSeed(child, obj.sub);
        head.append(child);
    }
    parent.append(head);
}

function innerSeed(parent, obj) {
    for (var index in obj) {
        var link = $("<a />").attr("tabindex", "-1").attr("href", "#").append(obj[index].linktext.toUpperCase());
        addClick(link, obj[index]);
        var subParent = $("<li />").append(link);
        parent.append(subParent);
        if (obj[index].sub.length > 0) {
            var child = $("<ul />").addClass("dropdown-menu").attr("role", "menu").attr("aria-labelledby", "dropdownMenu");
            innerSeed(child, obj[index].sub);
            subParent.addClass("dropdown-submenu").append(child);
        }
    }
}

function addClick(link, obj) {
    link.click(function () {
        pageload(obj.action);
        return false;
    });
}

function showSides() {
    $.ajax({
        url: "template/data/siteinfo.json?ts=" + ((new Date()).getTime()),
        type: "GET",
        dataType: "json",
        success: function (data) {
            doGetSideContent(0, data.maxAnnouncements);
        },
        error: function (err) {
            console.log(err);
        }
    });
}

function doGetSideContent(i, max) {
    fetch(i, function (html) {
        $("#announce").append($("<div />").addClass("span4").html(html));
        if (i + 1 < max) {
            doGetSideContent(i + 1, max);
        }
    });
}

function fetch(index, callback) {
    $.ajax({
        url: "template/data/announcements/an" + index + ".html?ts=" + ((new Date()).getTime()),
        type: "GET",
        dataType: "html",
        success: function (data) {
            callback(data);
        },
        error: function (err) {
            console.log(err);
        }
    });
}

function pageload(url, init) {
    console.log(url);
    if (!init) {
        $('html, body').animate({
            scrollTop: '350px'
        }, 'fast');
    }
    $.ajax({
        url: "template/data/pages/" + url + "?ts=" + ((new Date()).getTime()),
        type: "GET",
        dataType: "html",
        success: function (data) {
            $(".jumbotron").html(data);
        },
        error: function (err) {
            console.log(err);
        }
    });
}