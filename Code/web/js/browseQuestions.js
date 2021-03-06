//var baseUrl = "http://www.snap2ask.com/git/snap2ask/Code/web/api/index.php";
var baseUrl = "./api/index.php";
var hasPagedItems = false;

function addQuestion(questionData) {
    if (questionData.status != 0) {
        // Skip questions that have been answered
        return;
    }
    var image_url = questionData.image_url;
    if (!image_url) {
        // Skip questions with an invalid image url
        return;
    }
    var item = document.createElement('div');
    $(item).addClass('questionItem');
    var image = document.createElement('img');
    $(image).addClass('questionImage');
    image.setAttribute('src', image_url);
    var categoryLabel = document.createElement('label');
    $(categoryLabel).text('' + questionData.category);
    var subcategoryLabel = document.createElement('label');
    $(subcategoryLabel).text(' ' + questionData.subcategory);
    var dateLabel = document.createElement('label');
    $(dateLabel).text('Date: ' + questionData.date_created);
    item.appendChild(image);
    item.appendChild(categoryLabel);
    item.appendChild(subcategoryLabel);
    item.appendChild(dateLabel);
    $(item).click(function() {
        loadSelectedQuestion(questionData);
    });
    $('#pagedContent')[0].appendChild(item);
}

function loadSelectedQuestion(questionData) {
    window.location.href = 'viewQuestion.php?id=' + questionData.id;
}

function paginateItems() {
    if (!hasPagedItems && $('#pagedContent div').length == 0) {
        $('#browseError').text('No unanswered questions were found');
        return;
    }
    $("div.holder").jPages({
        containerID: "pagedContent",
        perPage: 15,
        callback: function(pages, items) {
            if (items.count == 0) {
                if (hasPagedItems) {
                    $('#browseError').text('No unanswered questions were found');
                    $("div.holder").jPages("destroy");
                }
            } else {
                $('#browseError').text('');
                hasPagedItems = true;
            }
        }
    });
}

function loadAllQuestions() {
    $('#searchLabel').text('');
    $('#browseNav li').removeClass('selected');
    $('#browseNav li:nth-child(1)').addClass('selected');
    // LOAD ALL QUESTIONS
    $('#pagedContent').empty();
    var jqxhr = $.get(baseUrl + "/questions", function(data) {
        for (var i = 0; i < data.length; i++) {
            // Add the question to the UI
            addQuestion(data[i]);
        }
        paginateItems();
    }).fail(function() {
            console.log("error loading questions");
        });
}

function loadCategoryQuestions(e) {
    $('#searchLabel').text('');
    $('#browseNav li').removeClass('selected');
    $('#browseNav li:nth-child(3)').addClass('selected');
    $('#pagedContent').empty();
    var categoryId = e.target.value;
    var jqxhr = $.get(baseUrl + "/categories/" + categoryId + "/questions", function(data) {
        for (var i = 0; i < data.length; i++) {
            // Add the question to the UI
            addQuestion(data[i]);
        }
        paginateItems();
    }).fail(function() {
            console.log("error loading questions");
        });
}

function loadPreferredSubjectQuestions() {
    $('#searchLabel').text('');
    $('#browseNav li').removeClass('selected');
    $('#browseNav li:nth-child(3)').addClass('selected');
    $('#pagedContent').empty();
    var validatedCategoryIds = $('#verified-categories-hidden')[0].value;
    var validatedCategoryNames = $('#verified-categories-names-hidden')[0].value;

    var categories = validatedCategoryIds.split(" ");
    var categoryNames = validatedCategoryNames.split(",");

    for (var k = 0; k < categories.length - 1; k++) {

        var categoryId = categories[k];

        // set async to false
        $.ajaxSetup({async:false});

        var jqxhr = $.get(baseUrl + "/categories/" + categoryId + "/questions", function(data) {

            if (data.length > 0) {
                var divider = document.createElement('div');
                $(divider).addClass('divider');
                $(divider).text(categoryNames[k]);
                $('#pagedContent')[0].appendChild(divider);
            }

            for (var i = 0; i < data.length; i++) {
                // Add the question to the UI
                addQuestion(data[i]);
            }

            if (k == categories.length - 1) {
                paginateItems();

                // Set async back to true
                $.ajaxSetup({async:true});
            }
        }).fail(function() {
                console.log("error loading questions");
            });
    }
}

function loadQuestionsFromSearch(searchQuery) {
    $('#pagedContent').empty();
    $('#searchLabel').text('Search Results For: ' + searchQuery);
    var searchData = {
        "search": searchQuery
    };
    $.ajax({
        type: 'POST',
        url: baseUrl + "/search/questions",
        data: JSON.stringify(searchData),
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        success: function(data) {
            for (var i = 0; i < data.length; i++) {
                // Add the question to the UI
                addQuestion(data[i]);
            }
            paginateItems();
        }
    });
}
// we wait for the DOM to load
$(document).ready(function() {
    var searchQueryElement = $('#search-query-hidden');
    var hasSearchQuery = searchQueryElement.length > 0 && searchQueryElement[0].value.length > 0;
    if (hasSearchQuery) {
        // Display search results
        var searchQuery = searchQueryElement[0].value;
        loadQuestionsFromSearch(searchQuery);
    } else {
        // Display all recent questions
        loadAllQuestions();
    }
    // Add click event listeners for each tab (all,categories,preffered)
    $('#browseNav li.mainLink:nth-child(1)').click(loadAllQuestions);
    $('#browseNav li.mainLink:nth-child(3)').click(loadPreferredSubjectQuestions);
    $('#browseNav li.subLink').click(loadCategoryQuestions);
});