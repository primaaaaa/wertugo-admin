document.addEventListener("DOMContentLoaded", function () {
    initTableFilter();
    initTableSearch();
    setActiveFilter();
});

function initTableFilter() {
    const filterButton = document.querySelector(".filter-btn");
    const filterItems = document.querySelectorAll(".filter-item");

    if (filterItems.length > 0) {
        filterItems.forEach((item) => {
            item.addEventListener("click", function (e) {
                e.preventDefault();

                const selectedStatus = this.dataset.filter;
                console.log("Selected:", selectedStatus);

                if (filterButton) {
                    filterButton.textContent = "Tampilkan " + selectedStatus;
                }

                const url = new URL(window.location.href);

                if (selectedStatus === "Semua") {
                    url.searchParams.delete("status");
                } else {
                    url.searchParams.set("status", selectedStatus);
                }

                url.searchParams.delete("page");
                window.location.href = url.toString();
            });
        });
    }
}

function setActiveFilter() {
    const filterButton = document.querySelector(".filter-btn");
    const urlParams = new URLSearchParams(window.location.search);
    const currentStatus = urlParams.get("status");

    if (filterButton && currentStatus) {
        filterButton.textContent = "Tampilkan " + currentStatus;
    }
}

function initTableSearch() {
    const searchInput = document.querySelector(".search-input");

    if (searchInput) {
        searchInput.addEventListener("keyup", function () {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll("tbody tr");

            rows.forEach((row) => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchValue) ? "" : "none";
            });
        });
    }
}
