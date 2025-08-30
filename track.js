let complaints = [];

function addComplaint() {
    const complaintText = document.getElementById("complaintText").value;
    if (!complaintText) return alert("Please enter a complaint!");

    const newComplaint = {
        text: complaintText,
        status: "Pending",
        progress: 0
    };

    complaints.push(newComplaint);
    document.getElementById("complaintText").value = "";//value to be inserterd by admin
    updateTable();
}

function updateStatus(index) {
    const statuses = ["Pending", "In Progress", "Resolved"];
    const nextStatus = (complaints[index].progress === 0) ? 1 : (complaints[index].progress === 50 ? 2 : 0);
    
    complaints[index].status = statuses[nextStatus];
    complaints[index].progress = nextStatus * 50;

    updateTable();
}

function updateTable() {
    const tableBody = document.getElementById("complaintTable");
    tableBody.innerHTML = "";

    complaints.forEach((complaint, index) => {
        const row = `
            <tr>
                <td>${complaint.text}</td>
                <td>${complaint.status}</td>
                <td>
                    <div class="progress-bar">
                        <div class="progress" style="width:${complaint.progress}%;">${complaint.progress}%</div>
                    </div>
                </td>
                <td><button onclick="updateStatus(${index})">Update</button></td>
            </tr>
        `;
        tableBody.innerHTML += row;
    });
}
