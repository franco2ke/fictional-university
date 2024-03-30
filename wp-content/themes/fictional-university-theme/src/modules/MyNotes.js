import $ from "jquery";

class MyNotes {
  constructor() {
    this.events();
  }

  events() {
    // Add Event Listeners
    // Use event bubbling to also add event listeners to dynamically rendered notes
    $("#my-notes").on("click", ".delete-note", this.deleteNote); // delete the note
    $("#my-notes").on("click", ".edit-note", this.editNote.bind(this)); // edit the note
    $("#my-notes").on("click", ".update-note", this.updateNote.bind(this)); // save note
    $(".submit-note").on("click", this.createNote.bind(this)); // save note
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

    if (thisNote.data("state") == "editable") {
      // state stored as data attribute
      this.makeNoteReadOnly(thisNote);
    } else {
      // make editable
      this.makeNoteEditable(thisNote);
    }
  }

  makeNoteEditable(thisNote) {
    // change 'Edit' button to 'Cancel' button
    thisNote
      .find(".edit-note")
      .html(`<i class="fa fa-times" aria-hidden="true"></i> Cancel</span>`);
    // make input elements editable
    thisNote
      .find(".note-title-field, .note-body-field")
      .removeAttr("readonly")
      .addClass("note-active-field");
    // make the button visible
    thisNote.find(".update-note").addClass("update-note--visible");
    // update note state
    thisNote.data("state", "editable");
  }

  makeNoteReadOnly(thisNote) {
    // change 'Cancel' button back to 'Edit' button
    thisNote
      .find(".edit-note")
      .html(`<i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>`);
    // make input elements non-editable
    thisNote
      .find(".note-title-field, .note-body-field")
      .attr("readonly", "readonly") // set attribute and value
      .removeClass("note-active-field");
    // hide the note save button
    thisNote.find(".update-note").removeClass("update-note--visible");
    // update data attribute representing state
    thisNote.data("state", "readonly");
  }

  // Update Note
  updateNote(e) {
    // get the list element to obtain the note ID from its data attribute
    let thisNote = $(e.target).parents("li");
    // store form data
    let ourUpdatedPost = {
      title: thisNote.find(".note-title-field").val(),
      content: thisNote.find(".note-body-field").val(),
    };
    // ajax method used to send POST/DELETE requests
    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader("X-WP-Nonce", universityData.nonce);
      },
      url:
        universityData.root_url + `/wp-json/wp/v2/note/${thisNote.data("id")}`,
      type: "POST",
      data: ourUpdatedPost,
      success: (response) => {
        this.makeNoteReadOnly(thisNote);
        console.log("Congrats");
        console.log(response);
      },
      error: (response) => {
        console.log("Sorry");
        console.log(response);
      },
    });
  }

  // NOTE: create Note
  createNote(e) {
    // store form data
    let ourNewPost = {
      title: $(".new-note-title").val(),
      content: $(".new-note-body").val(),
      status: "publish",
    };
    // ajax method used to send POST/DELETE requests
    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader("X-WP-Nonce", universityData.nonce);
      },
      url: universityData.root_url + `/wp-json/wp/v2/note/`,
      type: "POST",
      data: ourNewPost,
      success: (response) => {
        $(".new-note-title, .new-note-body").val(""); // empty input fields
        // create new note and prepend it to my-notes list
        $(`
        <li data-id="${response.id}">
          <input readonly class="note-title-field" type="text" value="${response.title.raw}">
          <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
          <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
          <textarea readonly class="note-body-field" name="" id="" cols="30" rows="10">${response.content.raw}</textarea>
          <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>
        </li>
        `)
          .prependTo("#my-notes")
          .hide()
          .slideDown();
        console.log("Congrats");
        console.log(response);
      },
      error: (response) => {
        console.log("Sorry");
        console.log(response);
      },
    });
  }
}

export default MyNotes;
