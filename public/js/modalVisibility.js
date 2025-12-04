function getJSON(content, attributeName) {
    console.log(content);
    console.log(attributeName);
    let titleOfModal = document.getElementById("titleOfModal");
    titleOfModal.innerText = attributeName;
    if (attributeName === "report_details") {
        let value = JSON.parse(content);
        document.getElementById("jsonDisplay").textContent = JSON.stringify(
            value,
            null,
            2
        );
        Prism.highlightElement(document.getElementById("jsonDisplay"));
    } else {
        let value = JSON.parse(content);
        let jsonDisplay = document.getElementById("jsonDisplay");
        jsonDisplay.innerHTML = "";
        Object.keys(value).forEach(function (key) {
            let val = value[key];
            let users = document.createElement("p");
            users.innerHTML = val;
            jsonDisplay.appendChild(users);
        });
    }
}
