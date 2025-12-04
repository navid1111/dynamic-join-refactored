function downloadTableAsExcel(filename) {
    let table = document.getElementById("content-table");
    let rows = table.querySelectorAll("tr");
    let csvContent = "";

    for (let row of rows) {
        let cells = row.querySelectorAll("th, td");
        let rowData = Array.from(cells)
            .map((cell) => cell.innerText)
            .join(",");
        csvContent += rowData + "\n";
    }

    let blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
    let url = URL.createObjectURL(blob);

    let a = document.createElement("a");
    a.href = url;
    a.download = `${filename}.csv`;
    document.body.appendChild(a);
    a.click();
    window.URL.revokeObjectURL(url);
    document.body.removeChild(a);
}