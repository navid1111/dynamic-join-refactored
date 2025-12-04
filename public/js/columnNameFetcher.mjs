export function columnNameRetriever() {
    // console.log("first");
    if ($(this).val() != "") {
        var currentId = $(this).attr("id");
        // console.log(currentId);
        var value = $(this).val();
        // console.log(value);
        var dependent = $(this).attr("data-dependent");
        // console.log(dependent);
        var _token = $('input[name="_token"').val();
        $.ajax({
            url: route("adminViewCreate.fetch"),
            method: "POST",
            data: {
                value: value,
                _token: _token,
                dependent: dependent,
            },
            success: function (response) {
                var columnName = document.getElementById(response.dependent);
                columnName.innerHTML = "";
                // response.data.forEach(function (entry, index) {
                //     var numberOfFeatures = Object.keys(entry).length;
                //     var columnOption = document.createElement("option");
                //     columnOption.textContent = entry;
                //     columnName.appendChild(columnOption);
                // });
                setUpColumnOptions(columnName, response, false);
            },
        });
    }
}

function setUpColumnOptions(columnName, response, isCheck) {
    response.data.forEach(function (entry, index) {
        var numberOfFeatures = Object.keys(entry).length;
        var columnOption = document.createElement("option");
        columnOption.textContent = entry;
        columnOption.value = entry;
        columnName.appendChild(columnOption);
    });
}
