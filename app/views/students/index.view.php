<style>
  .offcanvas-custom {
    position: fixed;
    top: 10%;
    left: 45%;
    transform: translate(50%, 50%);
    padding: 10px;
    width: 400px; /* Set your desired width */
    max-height: 80%; /* Optional: limit height */
    overflow-y: auto; /* Optional: enable scrolling */
}
</style>

<!--Alerts offcanvas-->
<div class="offcanvas offcanvas-end" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
  <div class="offcanvas-body">
    <div id="alert-box" class="alert">
        <button id="btn-close" type="button" class="btn-close bg-white close-alert float-end"  data-bs-dismiss="offcanvas" aria-label="Close"></button>
        <div class="alert-message" id="alert-message"></div>
    </div>
  </div>
</div>

<!--Import Student form-->
<div class="offcanvas offcanvas-custom" data-bs-backdrop="static" tabindex="-1" id="staticBackdrop" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasExampleLabel"><h3>Import Students</h3></h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close" id="close-upload"></button>
    </div>
    <div class="offcanvas-body">
      <div id="importResult"></div>

      

      <form id="importForm" enctype="multipart/form-data">
          <input type="file" name="csv_file" accept=".csv" class="btn btn-sm p-0" required>
          <button type="submit" class="btn btn-success btn-sm p-0 px-1">Upload</button>
      </form>
    </div>
</div>

<h1 class="mt-5">Students</h1>

<div class="d-flex justify-content-end">
  <a href="/web/students/create" class="btn btn-primary p-1 mb-3 d-flex justify-content-end" style="font-size: 0.8em;"><?=icon('person-plus-fill')?></a>
  <a href="/web/students/download" class="btn btn-success p-1 mb-3 d-flex justify-content-end ms-1" onclick="exportStudents(event)" style="font-size: 0.8em;" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling"><?=icon('cloud-arrow-down-fill')?></a>
  <a href="/web/students/updload" class="btn btn-danger p-1 mb-3 d-flex justify-content-end ms-1"  style="font-size: 0.8em;" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop" aria-controls="staticBackdrop"><?=icon('cloud-arrow-up-fill')?></a>
</div>
<div id="exportResult"></div>
<table class="table table-bordered">
  <thead>
    <tr>
      <th>#</th>
      <th>Student ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Phone</th>
      <th>Class</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody id="tbody">
  
  </tbody>
</table>

<script>
/*  const loadBtn = document.getElementById('save-user');
const resultEl = document.getElementById('result');

const alertBox = document.getElementById("alert-box");
const alertMessage = document.getElementById('alert-message');
const btnClose = document.getElementById('btn-close');

document.getElementById('create-user').hidden = true;


//Function for creating new user
document.getElementById("register").addEventListener("submit", function(e) {

    (() => {
  'use strict'
  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  const forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }

      form.classList.add('was-validated')
    }, false)
  })
})();
 
        e.preventDefault();
        e.stopPropagation();

        time = 5000;

        const form = e.target;
        const formData = new FormData(form);

        fetch('/api/register?apiKey=devKey123', { 
            method  : 'POST',
            body    :   formData,
            credentials: 'same-origin'
        })
        .then(response => {        
            //if (!response.ok) throw new Error('Network response was not ok: ' + response.status);
            return response.json();
        })
        .then(data => {console.log(data);       

            if (data.success) {
                alertBox.classList.remove('alert-danger');
                alertBox.classList.add('alert-success');
                alertMessage.innerHTML = data.message
                alertBox.hidden = false;
                btnClose.hidden = false;

                loadUsers();
                resetForm();
                
            } else {           
                
                alertBox.classList.remove('alert-success');
                alertBox.classList.add('alert-danger');
                alertMessage.innerHTML = data.message

                btnClose.hidden = false;
                alertBox.hidden = false;            

            }

        /* setTimeout(() =>{
            btnClose.hidden = true;
            alertBox.hidden = true;
        }, time); 

    return;
    })
    .catch(err => {
        console.error('Fetch error:', err);
        //resultEl.textContent = 'Failed to load students.';
    });
}) */


//Convert first letter of word to uppercase
function firstUpper(val) {
    return String(val).charAt(0).toUpperCase() + String(val).slice(1);
}

function resetForm() {
    document.getElementById('name').value = "";
    document.getElementById('email').value = "";
    document.getElementById('password').value = "";
    document.getElementById('role').selectedIndex = 0;
    return;
}

function loadUsers() {
    let tbody = document.getElementById('tbody');
    let tableForm = '';
    fetch("/api/students?apiKey=devKey123", {
        method: "get",
        credentials: 'same-origin'
    })
    .then(res => res.json())
    .then(data => {//console.log(data);
        numbering = 1;
        if (data.success) {
            students = data.students;
            students.forEach((element) => {
                tableForm += `<tr>
                    <td>${numbering}</>
                    <td>${element.student_no}</>
                    <td>${element.first_name} ${element.other_name} ${element.last_name}</td>
                    <td>${element.email}</td>
                    <td>${element.phone}</td>
                    <td>${element.class_id}</td>
                    
                    <td>
                        <a href="/web/admin/users/${element['id']}/edit" class="btn btn-sm btn-warning p-1 py-0 pb-1"><img src="/../assets/images/bootstrap-icons/pencil-square.svg" alt=""></a>
                        <a href="/web/admin/users/${element['id']}/delete" class="btn btn-sm btn-danger p-1 py-0 pb-1"><img src="/../assets/images/bootstrap-icons/unlock-fill.svg" alt=""></a>
                    </td>
                    </tr>` 

                numbering++;
            })
                
        }
        tbody.innerHTML = tableForm;return;
    })
    .catch(err => {
        console.error(err);
    });
}

loadUsers();

function exportStudents (e) {
   const alertBox = document.getElementById("alert-box");
const alertMessage = document.getElementById('alert-message');
  const btnClose = document.getElementById('btn-close');

  e.preventDefault();

  fetch("/api/students/download?apiKey=devKey123")
    .then(res =>  {
        if (!res.ok) {
            throw new Error("Network error while exporting CSV");
        }
        return res.blob(); // get response as Blob
    })
    .then(blob => {console.log(blob);     
    
        if (blob) {
           // Create a temporary download link
          const url = window.URL.createObjectURL(blob);
          const a = document.createElement("a");
          a.href = url;
          a.download = "students_export_" + new Date().toISOString().slice(0,19).replace(/:/g,"-") + ".csv";
          document.body.appendChild(a);
          a.click();
          a.remove();
          window.URL.revokeObjectURL(url);
          
          alertBox.classList.remove('alert-danger');
          alertBox.classList.add('alert-success');
          alertMessage.innerHTML = "✅ File download generated successfully"
          alertBox.hidden = false;
          btnClose.hidden = false;

            
                
        } else {
            alertBox.classList.remove('alert-success');
            alertBox.classList.add('alert-danger');
            alertMessage.innerHTML = "❌ Export failed.";
           btnClose.hidden = false;
            alertBox.hidden = false; 
           
        }

        setTimeout( function() {
          alertMessage.innerHTML = ""
          alertBox.hidden = true;
          btnClose.click();
        }, 5000);
    });
}

function importStudents () {
  document.getElementById("importForm").addEventListener("submit", function(e) {
    const closeUpload = document.getElementById('close-upload');

    e.preventDefault();

    let formData = new FormData(this);

      fetch("/api/students/upload?apiKey=devKey123", {
          method: "POST",
          body: formData
      })
      .then(res => res.json())
      .then(data => {console.log(data);
      
        if (data.success) {
           document.getElementById("importResult").innerHTML = data.message;
           loadUsers()
        }else {
            document.getElementById("importResult").innerHTML = data.message;
        }
          

        setTimeout(function () {
            closeUpload.click();
            document.getElementById("importResult").innerHTML = '';
        },3000)
      });
    });

}

function previewUpload() {
  
}
    
/* const btnAddUser = document.getElementById('add-user');
const btnCloseAddUser = document.getElementById('close-adduser');

btnAddUser.addEventListener('click', () => {
    //alert()
    document.getElementById('create-user').hidden = false;
});

btnCloseAddUser.addEventListener('click', () => {
    //alert()
    document.getElementById('create-user').hidden = true;
})

btnClose.hidden = true;
alertBox.hidden = true;

btnClose.addEventListener('click', () => {
    btnClose.hidden = true;
    alertBox.hidden = true;
    alertMessage.innerHTML = ''
}) */
</script>