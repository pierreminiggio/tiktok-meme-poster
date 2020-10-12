window.addEventListener('paste', e => {
    const files = e.clipboardData.files
    if (files.length && files[0] instanceof File) {
        const image = files[0]
        console.log(image)
    }
});