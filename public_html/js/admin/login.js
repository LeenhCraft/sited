$(document).ready(function () {
  $("#frmlogin").submit(function (event) {
    event.preventDefault();
    const form = $(this).serialize();
    const url = base_url + "admin/login";
    $.post(url, form, function () {})
      .done(function (data) {
        const { status, message, data: responseData } = data;
        const titleMessage = status ? `${message}<br>${responseData}` : message;
        Swal.fire({
          title: titleMessage,
          icon: status ? "success" : "error",
          confirmButtonText: "OK",
        });
        if (status) {
          setTimeout(() => window.location.reload(), 2000);
        }
      })
      .fail(function (jqXHR, textStatus, errorThrown) {
        Swal.fire({
          title: "Error",
          text: "An error occurred while processing your request.",
          icon: "error",
          confirmButtonText: "OK",
        });
        console.log(jqXHR, textStatus, errorThrown);
      });
  });
});
