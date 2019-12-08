/*Handlebar js Helpers*/
Handlebars.registerHelper('formatDate', function(dateString) {
    return new Handlebars.SafeString(
        moment(dateString).format("DD/MM/YYYY hh:mm a").toUpperCase()
    );
});

Handlebars.registerHelper('titleize', function(str) {
    var title = str.replace(/[- _]+/g, ' ');
    var words = title.split(' ');
    var len = words.length;
    var res = [];
    var j = 0;
    while (len--) {
        var word = words[j++];
        var splitStr = word.toLowerCase().split(' ');
        for (var i = 0; i < splitStr.length; i++) {
            splitStr[i] = splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1);
        }
        res.push(splitStr.join(' '));
    }
    return res.join(' ');
});
