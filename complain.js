document.getElementById("complaint-form").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent actual form submission

    let title = document.getElementById("title").value;
    let location = document.getElementById("location").value;
    let description = document.getElementById("description").value;
    let photo = document.getElementById("photo").files[0];

    if (!title || !location || !description || !photo) {
        alert("Please fill in all fields and upload a photo.");
        return;
    }

    document.getElementById("message").innerHTML = 
        `<p style="color: green; text-align: center;">Complaint Submitted Successfully!</p>`;

    // Optional: Clear the form after submission
    document.getElementById("complaint-form").reset();
    document.getElementById("preview").style.display = "none";
});

// Preview Image Before Upload
function previewImage(event) {
    let reader = new FileReader();
    reader.onload = function(){
        let output = document.getElementById("preview");
        output.src = reader.result;
        output.style.display = "block";
    };
    reader.readAsDataURL(event.target.files[0]);
}
