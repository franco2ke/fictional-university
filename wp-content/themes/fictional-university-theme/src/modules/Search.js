import $ from "jquery";

class Search {
  // 1. describe and create/initialize our object
  constructor() {
    // add the search html code to body
    this.addSearchHTML();
    // select dom elements
    this.resultsDiv = $("#search-overlay__results");
    this.openButton = $(".js-search-trigger");
    this.closeButton = $(".search-overlay__close");
    this.searchOverlay = $(".search-overlay");
    this.searchField = $("#search-term");
    // inititalize events and their listeners
    this.events();
    // set up state
    this.isOverlayOpen = false; // to listen to 's' and 'esc' keypress when overlay is open
    this.isSpinnerVisible = false; // to not reset spinner if already running, to not run spinner if searchfield is empty after change
    this.previousValue; // to not run spinner if value has not changed, ignore useless keypresses
    this.typingTimer;
  }

  // 2. events / respond to events and call appropriate actions
  events() {
    // add event listeners and callback methods
    this.openButton.on("click", this.openOverlay.bind(this));
    this.closeButton.on("click", this.closeOverlay.bind(this));
    // listen for key shortcuts on the main document
    // open search overlay on 's' keypress, close on 'esc' keypress
    $(document).on("keydown", this.keyPressDispatcher.bind(this));
    // listen for key press on the search input
    this.searchField.on("keyup", this.typingLogic.bind(this));
    // NOTE: keydown fires very many times during a keypress, keyup fires only once
  }

  // 3. methods (functions, actions...)
  openOverlay() {
    this.searchOverlay.addClass("search-overlay--active");
    // empty searchfied each time overlay is opened
    this.searchField.val("");
    // focus on searchfield
    setTimeout(() => this.searchField.trigger("focus"), 301);
    // disable page scrolling when search overlay is open
    $("body").addClass("body-no-scroll");
    // manage state
    this.isOverlayOpen = true;

    return false; // like e.preventDefault()
  }

  closeOverlay() {
    this.searchOverlay.removeClass("search-overlay--active");
    // enable page scrolling when search overlay is closed
    $("body").removeClass("body-no-scroll");
    // state management
    this.isOverlayOpen = false;
  }

  // Typing Logic for Search field, on every keypress
  typingLogic() {
    // when searchfield value changes, run spinner and reset timeout
    // ignore non-character keys
    if (this.searchField.val() != this.previousValue) {
      clearTimeout(this.typingTimer);

      // Only run if searchfield has content
      if (this.searchField.val()) {
        // run spinner if not already running
        if (!this.isSpinnerVisible) {
          // replace resultsDiv content with the spinner
          this.resultsDiv.html('<div class="spinner-loader"></div>');
          this.isSpinnerVisible = true;
        }
        // set max period between keypresses before sending search request
        this.typingTimer = setTimeout(this.getResults.bind(this), 750);
      } else {
        // Run if searchfield is empty
        this.resultsDiv.html(""); // empty results / spinner
        this.isSpinnerVisible = false;
        // No need to get search results if searchfield is empty
      }
    }
    // set state value
    this.previousValue = this.searchField.val();
  }

  getResults() {
    $.getJSON(
      `${
        universityData.root_url
      }/wp-json/university/v1/search?term=${this.searchField.val()}`,
      (results) => {
        this.resultsDiv.html(`
        <div class="row">
          <div class="one-third">
            <h2 class="search-overlay__section-title">General Information</h2>
            ${
              // conditional rendering of info using ternary operator
              results.generalInfo.length
                ? '<ul class="link-list min-list">'
                : "<p>No general information matches that search</p>"
            }
            ${results.generalInfo
              .map(
                (item) =>
                  `<li><a href="${item.permalink}">${item.title}</a> ${
                    item.PostType === "post" ? `by ${item.authorName}` : ""
                  }</li>`
              )
              .join("")}
            ${results.generalInfo.length ? "</ul>" : ""}
          </div>
          <div class="one-third">
            <h2 class="search-overlay__section-title">Programs</h2>
            ${
              // conditional rendering of info using ternary operator
              results.programs.length
                ? '<ul class="link-list min-list">'
                : `<p>
                    No programs match that search. 
                    <a href="${universityData.root_url}/programs">View all programs</a>
                   </p>`
            }
            ${results.programs
              .map(
                (item) =>
                  `<li><a href="${item.permalink}">${item.title}</a></li>`
              )
              .join("")}
            ${results.programs.length ? "</ul>" : ""}

            <h2 class="search-overlay__section-title">Professors</h2>
            ${
              // conditional rendering of info using ternary operator
              results.professors.length
                ? '<ul class="professor-cards">'
                : `<p>
                    No professors match that search. 
                   </p>`
            }
            ${results.professors
              .map(
                (item) => `
                  <li class="professor-card__list-item">
                    <a class="professor-card" href="${item.permalink}">
                      <img class="professor-card__image" src="${item.image}" alt="">
                      <span class="professor-card__name">${item.title}</span>
                    </a>
                  </li>
                `
              )
              .join("")}
            ${results.professors.length ? "</ul>" : ""}
          </div>

          <div class="one-third">
            <h2 class="search-overlay__section-title">Campuses</h2>
            ${
              // conditional rendering of info using ternary operator
              results.campuses.length
                ? '<ul class="link-list min-list">'
                : `<p>No campuses match that search <a href="${universityData.root_url}/campus">View all campuses</a> </p>`
            }
            ${results.campuses
              .map(
                (item) =>
                  `<li><a href="${item.permalink}">${item.title}</a> ${
                    item.PostType === "post" ? `by ${item.authorName}` : ""
                  }</li>`
              )
              .join("")}
            ${results.campuses.length ? "</ul>" : ""}

            <h2 class="search-overlay__section-title">Events</h2>
            ${
              // conditional rendering of info using ternary operator
              results.events.length
                ? '<ul class="link-list min-list">'
                : `<p>No events match that search <a href="${universityData.root_url}/events">View all events</a> </p>`
            }
            ${results.events
              .map(
                (item) =>
                  `
                  <div class="event-summary">
                  <a class="event-summary__date t-center" href="${item.permalink}">
                    <span class="event-summary__month">${item.month}</span>
                    <span class="event-summary__day">${item.day}</span>
                  </a>
                  <div class="event-summary__content">
                    <h5 class="event-summary__title headline headline--tiny"><a href="${item.permalink}">${item.title}</a></h5>
                    <p>
                      ${item.description}
                      <a href="${item.permalink}" class="nu gray">Learn more</a>
                    </p>
                  </div>
                </div> 
                  `
              )
              .join("")}
          </div>
        </div>
      `);
        // update spinner state
        this.isSpinnerVisible = false;
      }
    );
  }

  // handle search opening / closing keypresses
  keyPressDispatcher(e) {
    // open overlay on 's' keypress only if its closed
    if (
      e.keyCode === 83 &&
      !this.isOverlayOpen &&
      // avoid 's' keypress on other on-page inputs opening search overlay
      !$("input, textarea").is(":focus")
    ) {
      this.openOverlay();
    }
    // close open overlay on 'esc' keypress else ignore
    if (e.keyCode === 27 && this.isOverlayOpen) {
      this.closeOverlay();
    }
  }

  // add search markup via JS
  addSearchHTML() {
    $("body").append(`
    <div class="search-overlay">
      <div class="search-overlay__top">
        <div class="container">
          <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
          <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term" autocomplete="off">
          <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
        </div>
      </div>

      <div class="container">
        <div id="search-overlay__results"></div>
      </div>
    </div>`);
  }
}

export default Search;
