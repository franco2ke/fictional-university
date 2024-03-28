import $ from "jquery";

class MyNotes {
  constructor() {
    this.events();
  }

  events() {
    $(".delete-note").on("click", this.deleteNote); // delete the note
    $(".edit-note").on("click", this.editNote); // edit the note
  }

  // Methods
  deleteNote(e) {
    // get the list element to obtain the note ID from its data attribute
    let thisNote = $(e.target).parents("li");
    // ajax method used to send POST/DELETE requests
    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader("X-WP-Nonce", universityData.nonce);
      },
      url:
        universityData.root_url + `/wp-json/wp/v2/note/${thisNote.data("id")}`,
      type: "DELETE",
      success: (response) => {
        thisNote.slideUp();
        console.log("Congratso");
        console.log(response);
      },
      error: (response) => {
        console.log("Sorry");
        console.log(response);
      },
    });
  }

  // Note, edit the note
  editNote(e) {
    // get the list element to obtain the note ID from its data attribute
    let thisNote = $(e.target).parents("li");
    // make input elements editable
    thisNote
      .find(".note-title-field, .note-body-field")
      .removeAttr("readonly")
      .addClass("note-active-field");
    // make the button visible
    thisNote.find(".update-note").addClass("update-note--visible");
  }
}

export default MyNotes;
