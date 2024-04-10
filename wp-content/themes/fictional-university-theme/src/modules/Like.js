import $ from "jquery";

class Like {
  constructor() {
    this.events();
  }

  events() {
    $(".like-box").on("click", this.ourClickDispatcher.bind(this));
  }

  // methods
  ourClickDispatcher(e) {
    let currentLikeBox = $(e.target).closest(".like-box");

    // check value of data attribute
    if (currentLikeBox.attr("data-exists") == "yes") {
      // for repeated access use element.attr() rather than element.data()
      this.deleteLike(currentLikeBox);
    } else {
      this.createLike(currentLikeBox);
    }
  }

  createLike(currentLikeBox) {
    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader("X-WP-Nonce", universityData.nonce);
      }, // for user identification / security
      url: universityData.root_url + "/wp-json/university/v1/manageLike",
      type: "POST",
      data: { professorId: currentLikeBox.data("professor") },
      success: (response) => {
        currentLikeBox.attr("data-exists", "yes"); // set data attribute
        let likeCount = parseInt(currentLikeBox.find(".like-count").html(), 10); // convert string to num
        likeCount++;
        currentLikeBox.find(".like-count").html(likeCount);
        currentLikeBox.attr("data-like", response); // the like post ID
        console.log(response);
      },
      error: (response) => {
        console.log(response);
        // console.log(this);
      },
    });
  }

  deleteLike(currentLikeBox) {
    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader("X-WP-Nonce", universityData.nonce);
      }, // for user identification / security
      url: universityData.root_url + "/wp-json/university/v1/manageLike",
      type: "DELETE",
      data: { like: currentLikeBox.attr("data-like") },
      success: (response) => {
        currentLikeBox.attr("data-exists", ""); // set data attribute
        let likeCount = parseInt(currentLikeBox.find(".like-count").html(), 10); // convert string to num
        likeCount--;
        currentLikeBox.find(".like-count").html(likeCount);
        currentLikeBox.attr("data-like", response); // the like post ID
        console.log(response);
      },
      error: (response) => {
        console.log(response);
      },
    });
  }
}

export default Like;
