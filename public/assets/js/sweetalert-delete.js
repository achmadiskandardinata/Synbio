function deleteData(dataId) {
    Swal.fire({
        title: "Apakah Anda yakin ingin menghapus data ini?",
        text: "Data ini dihapus secara permananen!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, hapus saja!"
    }).then((result) => {
        if (result.isConfirmed) {
            // //   Swal.fire({
            // //     title: "Terhapus!",
            // //     text: "File Anda telah dihapus.",
            // //     icon: "success"
            //   });
            $('#deleteForm' + dataId).submit();
        }
    });

}
