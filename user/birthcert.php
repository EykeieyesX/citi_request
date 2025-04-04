<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Birth Records</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

    <div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-gray-700 mb-4">Birth Records</h1>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="py-2 px-4 border">ID</th>
                        <th class="py-2 px-4 border">Child Name</th>
                        <th class="py-2 px-4 border">Sex</th>
                        <th class="py-2 px-4 border">Birth Date</th>
                        <th class="py-2 px-4 border">Father</th>
                        <th class="py-2 px-4 border">Mother</th>
                        <th class="py-2 px-4 border">Status</th>
                    </tr>
                </thead>
                <tbody id="birthRecords"></tbody>
            </table>
        </div>
    </div>

    <script>
       async function fetchData() {
    try {
        const response = await fetch("https://civilregistrar.lgu2.com/api/birth.php", {
            method: "GET",
            headers: {
                "Content-Type": "application/json"
            }
        });

        if (!response.ok) {
            throw new Error(HTTP error! Status: ${response.status});
        }

        const data = await response.json();

        const tableBody = document.getElementById("birthRecords");
        tableBody.innerHTML = "";

        data.forEach(record => {
            const row = document.createElement("tr");
            row.innerHTML = 
                <td class="py-2 px-4 border text-center">${record.id}</td>
                <td class="py-2 px-4 border">${record.child_first_name} ${record.child_middle_name} ${record.child_last_name}</td>
                <td class="py-2 px-4 border text-center">${record.child_sex}</td>
                <td class="py-2 px-4 border text-center">${record.child_date_of_birth}</td>
                <td class="py-2 px-4 border">${record.father_first_name} ${record.father_last_name}</td>
                <td class="py-2 px-4 border">${record.mother_first_name} ${record.mother_last_name}</td>
                <td class="py-2 px-4 border text-center font-semibold ${
                    record.status === "completed" ? "text-green-600" : "text-red-600"
                }">${record.status}</td>
            ;
            tableBody.appendChild(row);
        });
    } catch (error) {
        console.error("Error fetching data:", error);
    }
}

fetchData();

    </script>
</body>
</html>