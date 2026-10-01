        </main>
    </div>

    <!-- Scripts -->
    <script>
        // Sidebar Mobile Toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        const adminSidebar = document.getElementById('adminSidebar');
        if (sidebarToggle && adminSidebar) {
            sidebarToggle.addEventListener('click', () => {
                adminSidebar.classList.toggle('show');
            });
        }

        // Live Status Toggle with AJAX
        document.querySelectorAll('.js-status-toggle').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const modelId = this.dataset.id;
                const newStatus = this.checked ? 'active' : 'inactive';
                const statusBadge = document.getElementById('status-badge-' + modelId);
                
                fetch('model-status.php?id=' + modelId + '&status=' + newStatus + '&ajax=1')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (statusBadge) {
                                statusBadge.className = 'badge ' + (newStatus === 'active' ? 'badge-active' : 'badge-inactive');
                                statusBadge.textContent = newStatus === 'active' ? 'Active' : 'Inactive';
                            }
                        } else {
                            alert(data.message || 'Failed to update status');
                            this.checked = !this.checked; // Revert
                        }
                    })
                    .catch(err => {
                        console.error('Error toggling status:', err);
                        this.checked = !this.checked; // Revert
                    });
            });
        });

        // Delete confirmation
        function confirmDelete(name) {
            return confirm('Are you sure you want to permanently delete "' + name + '"? This action cannot be undone.');
        }

        // Image Preview Handler
        function previewImage(input, previewElementId) {
            const preview = document.getElementById(previewElementId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (preview) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>
