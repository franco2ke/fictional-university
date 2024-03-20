class Search {
  // 1. describe and create/initiate our object
  constructor() {
    this.addSearchHTML();
    this.openButton = document.querySelector(".search-trigger");
    this.closeButton = document.querySelector(".search-overlay__close");
    this.searchOverlay = document.querySelector(".search-overlay");
    this.searchField = document.querySelector("#search-term");
    this.resultsDiv = document.querySelector("#search-overlay__results");
    this.isSpinnerVisible = false;
    this.previousValue;
    this.events();
    this.isOverlayOpen = false;
    this.typingTimer;
  }

  // 2. events
  events() {
    this.openButton.addEventListener("click", () => this.openOverlay());
    this.closeButton.addEventListener("click", () => this.closeOverlay());
    document.body.addEventListener(
      "keydown",
      this.keyPressDispatcher.bind(this)
    );
    document.body.addEventListener("keyup", this.typingLogic.bind(this));
  }

  // 3. methods (function, action...)
  typingLogic() {
    // only perform search if searchfield value changes
    if (this.searchField.value != this.previousValue) {
      clearTimeout(this.typingTimer);

      if (this.searchField.innerHTML === "") {
        if (!this.isSpinnerVisible) {
          this.resultsDiv.innerHTML = `<div class="spinner-loader"></div>`;
          this.isSpinnerVisible = true;
        }
        this.typingTimer = setTimeout(this.getResults.bind(this), 750);
      } else {
        this.resultsDiv.innerHTML = "";
        this.isSpinnerVisible = false;
      }
    }

    this.previousValue = this.searchField.value;
  }

  getResults() {
    let searchTerm = this.searchField.value;
    let urls = [
      `${universityData.root_url}/wp-json/wp/v2/posts?search=${searchTerm}`,
      `${universityData.root_url}/wp-json/wp/v2/pages?search=${searchTerm}`,
    ];
    let combinedResults = [];

    let requests = urls.map((url) =>
      fetch(url)
        .then((res) => res.json())
        .catch(
          (err) =>
            (this.resultsDiv.innerHTML = `<p>unexpected error; please try again</p>`)
        )
    );

    Promise.all(requests).then((results) => {
      combinedResults = results.flat();

      this.resultsDiv.innerHTML = `<h2 class="search-overlay__section-title">General Information</h2>
    ${
      combinedResults.length
        ? '<ul class="link-list min-list">'
        : "<p>No general information matches that search.</p>"
    }
      ${combinedResults
        .map(
          (item) =>
            `<li><a href="${item.link}">${item.title.rendered} (${item.type})</a></li>`
        )
        .join("")}
        ${combinedResults.length ? "</ul>" : ""}
    `;
      this.isSpinnerVisible = false;
    });
  }

  keyPressDispatcher(e) {
    // S Key && document.querySelector('input textarea').activeElement
    if (e.keyCode === 83 && !this.isOverlayOpen) {
      this.openOverlay();
      this.isOverlayOpen = true;
    }
    // Escape Key
    if (e.keyCode === 27 && this.isOverlayOpen) {
      this.closeOverlay();
      this.isOverlayOpen = false;
    }
  }

  openOverlay() {
    this.searchOverlay.classList.add("search-overlay--active");
    // adds css property overflow:hidden
    document.body.classList.add("body-no-scroll");
    // clears search field on overlay open
    this.searchField.innerHTML = "";
    // waits for overlay to fade in before focusing cursur in input field.
    setTimeout(() => this.searchField.focus(), 750);
    this.isOverlayOpen = true;
  }

  closeOverlay() {
    this.searchOverlay.classList.remove("search-overlay--active");
    document.body.classList.remove("body-no-scroll");
  }

  addSearchHTML() {
    document.body.innerHTML += `
      <div class="search-overlay">
 
      <div class="search-overlay__top">
 
        <div class="container">
          <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
          <input type="text" id="search-term" class="search-term" placeholder="What are you looking for?">
          <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
        </div>
 
      </div>
 
      <div class="container">
        <div id="search-overlay__results">
        </div>
      </div>
 
    </div>
    `;
  }
}

/* 
for whatever reason your line 31 ( if (this.searchField.innerHTML === '') { ) would not work for me at all. It wouldn't read the input field as blank, and would always display a value. If you're unlucky like me that this is the case, the answer for me was to change that line instead to:

if(this.searchField.value)

-----------
hanks. Don't know why but I cant get those two to work:

this.openButton.addEventListener('click', () => this.openOverlay());
this.closeButton.addEventListener('click', () => this.closeOverlay());
*/

export default Search;
