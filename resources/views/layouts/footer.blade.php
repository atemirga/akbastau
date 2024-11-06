<footer class="footer footer-black footer-white">
    <div class="container-fluid">
        <div class="row">
            <div class="credits ml-auto">
                <span class="copyright">
                    ©
                    <script>
                        document.write(new Date().getFullYear().toString())
                    </script>{{ __(', made with ') }}<i class="fa fa-heart heart"></i>{{ __(' by ') }}<a class="@if(Auth::guest()) text-white @endif">{{ __('PLUR') }}</a>
                </span>
            </div>
        </div>
    </div>

    <!-- Переключение темы -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            if (localStorage.getItem("theme") === "dark") {
                document.body.classList.add("dark");
                const themeToggleText = document.getElementById("themeToggleText");
                if (themeToggleText) {
                    themeToggleText.textContent = "{{ __('Switch to Light Mode') }}";
                }
            }
        });

        function toggleTheme() {
            const body = document.body;
            const themeToggleText = document.getElementById("themeToggleText");

            if (body.classList.contains("dark")) {
                body.classList.remove("dark");
                if (themeToggleText) themeToggleText.textContent = "{{ __('Switch to Dark Mode') }}";
                localStorage.setItem("theme", "light");
            } else {
                body.classList.add("dark");
                if (themeToggleText) themeToggleText.textContent = "{{ __('Switch to Light Mode') }}";
                localStorage.setItem("theme", "dark");
            }
        }
    </script>
</footer>
