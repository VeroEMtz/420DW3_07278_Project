function clearForm() {
    $('#userForm').get(0).reset();
    $("#createUsrBtn").prop("disabled", false);
    //$("#clear-button").prop("disabled", true);
    $("#updateUsrBtn").prop("disabled", true);
    $("#deleteUsrBtn").prop("disabled", true);
    document.getElementById("user-selector").value = "";
}

function updateClearButtonState() {
    let dirtyElements = $("#userForm")
        .find('*')
        .filter(":input")
        .filter((index, element) => {
            return $(element).val();
        });
    if (dirtyElements.length > 0) {
        $("#clear-button").prop("disabled", false);
    } else {
        $("#clear-button").prop("disabled", true);
    }
}

function getFormDataAsUrlEncoded() {
    let formData = new FormData();
    formData.set("usrName", $("#username").val());
    formData.set("password", $("#password").val());
    formData.set("email", $("#email").val());
    // Add more fields as needed
    return (new URLSearchParams(formData)).toString();
}

function fillFormFromResponseObject(entityObject) {
    if ('usrName' in entityObject) {
        $("#username").val(entityObject.usrName);
    }
    if ('password' in entityObject) {
        $("#password").val(entityObject.password);
    }
    if ('email' in entityObject) {
        $("#email").val(entityObject.email);
    }
    // Fill other fields as needed
    $("#createUsrBtn").prop("disabled", true);
    //$("#clear-button").prop("disabled", false);
    $("#updateUsrBtn").prop("disabled", false);
    $("#deleteUsrBtn").prop("disabled", false);
}

function displayResponseError(responseErrorObject) {
    // Similar implementation as before
}

function loadUser() {
    let selectedUserId = document.getElementById("user-selector").value;
    
    const options = {
        "url": `${API_USER_URL}?userId=${selectedUserId}`,
        "method": "get",
        "dataType": "json"
    };
    
    $.ajax(options)
     .done((data, status, jqXHR) => {
         console.log("Received data: ", data);
         fillFormFromResponseObject(data);
     })
     .fail((jqXHR, textstatus, error) => {
         if ('responseJSON' in jqXHR && typeof jqXHR.responseJSON === "object") {
             displayResponseError(jqXHR.responseJSON);
         }
     });
}

function createUser() {
    const options = {
        "url": `${API_USER_URL}`,
        "method": "post",
        "data": getFormDataAsUrlEncoded(),
        "dataType": "json"
    };
    
    $.ajax(options)
     .done((data, status, jqXHR) => {
         console.log("Received data: ", data);
         fillFormFromResponseObject(data);
     })
     .fail((jqXHR, textstatus, error) => {
         if ('responseJSON' in jqXHR && typeof jqXHR.responseJSON === "object") {
             displayResponseError(jqXHR.responseJSON);
         }
     });
}

function updateUser() {
    const options = {
        "url": `${API_USER_URL}`,
        "method": "put",
        "data": getFormDataAsUrlEncoded(),
        "dataType": "json"
    };
    
    $.ajax(options)
     .done((data, status, jqXHR) => {
         console.log("Received data: ", data);
         fillFormFromResponseObject(data);
     })
     .fail((jqXHR, textstatus, error) => {
         if ('responseJSON' in jqXHR && typeof jqXHR.responseJSON === "object") {
             displayResponseError(jqXHR.responseJSON);
         }
     });
}

function deleteUser() {
    const options = {
        "url": `${API_USER_URL}`,
        "method": "delete",
        "data": getFormDataAsUrlEncoded(),
        "dataType": "json"
    };
    
    $.ajax(options)
     .done((data, status, jqXHR) => {
         console.log("Received data: ", data);
         clearForm();
     })
     .fail((jqXHR, textstatus, error) => {
         if ('responseJSON' in jqXHR && typeof jqXHR.responseJSON === "object") {
             displayResponseError(jqXHR.responseJSON);
         }
     });
}

document.getElementById("listUsrBtn").onclick = loadUser;
//document.getElementById("clear-button").onclick = clearForm;
document.getElementById("createUsrBtn").onclick = createUser;
document.getElementById("updateUsrBtn").onclick = updateUser;
document.getElementById("deleteUsrBtn").onclick = deleteUser;
$("#userForm").on("change", ":input", updateClearButtonState);
