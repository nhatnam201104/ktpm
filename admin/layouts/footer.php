
</main>
    </div>
    </div>
    <script>
        function getRole() {
        let val = document.getElementById("roleID").value;
        // Kiểm tra nếu giá trị khác 0 thì mới điều hướng
        if (val != 0) {
            location.href = "privilege.php?id=" + val;
        } else {
            alert("Vui lòng chọn lại");
        }
    }
    </script>
</body>
</html>