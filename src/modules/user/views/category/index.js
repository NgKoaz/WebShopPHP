const onStart = () => {
    const closeFilterBtn = document.querySelector(".filter-modal .top .close-btn");
    closeFilterBtn.onclick = () => {
        document.querySelectorAll(".filter-modal, .overlay").forEach((e) => {
            e.style.display = "none";
        })
    }
}

// document.onre = onStart;