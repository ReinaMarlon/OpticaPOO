<div class="admin-dashboard">
    <div class="admin-sidebar">
        <ul class="admin-menu">
            <li class="menu-item active" data-target="users-panel">
                <i class="fas fa-users"></i> Gestión de Usuarios
            </li>
            <li class="menu-item" data-target="roles-panel">
                <i class="fas fa-user-tag"></i> Roles y Permisos
            </li>
            <li class="menu-item" data-target="inventory-panel">
                <i class="fas fa-boxes"></i> Inventario
            </li>
            <li class="menu-item" data-target="reports-panel">
                <i class="fas fa-chart-bar"></i> Reportes
            </li>
            <li class="menu-item" data-target="settings-panel">
                <i class="fas fa-cog"></i> Configuración
            </li>
        </ul>
    </div>

    <div class="admin-content">
        <!-- Users Panel -->
        <div class="admin-panel active" id="users-panel">
            <h2>Gestión de Usuarios</h2>
            <div class="panel-actions">
                <button class="btn btn-primary" id="add-user-btn">
                    <i class="fas fa-plus"></i> Agregar Usuario
                </button>
                <input type="text" id="user-search" placeholder="Buscar usuario...">
            </div>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="users-table-body">
                    <!-- User data will be loaded here via AJAX -->
                </tbody>
            </table>
        </div>

        <!-- Roles Panel -->
        <div class="admin-panel" id="roles-panel">
            <h2>Roles y Permisos</h2>
            <div class="panel-actions">
                <button class="btn btn-primary" id="add-role-btn">
                    <i class="fas fa-plus"></i> Agregar Rol
                </button>
            </div>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre del Rol</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="roles-table-body">
                    <!-- Role data will be loaded here via AJAX -->
                </tbody>
            </table>
        </div>

        <!-- Inventory Panel -->
        <div class="admin-panel" id="inventory-panel">
            <h2>Gestión de Inventario</h2>
            <div class="panel-actions">
                <button class="btn btn-primary" id="add-product-btn">
                    <i class="fas fa-plus"></i> Agregar Producto
                </button>
                <input type="text" id="inventory-search" placeholder="Buscar producto...">
            </div>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Stock</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="inventory-table-body">
                    <!-- Inventory data will be loaded here via AJAX -->
                </tbody>
            </table>
        </div>

        <!-- Reports Panel -->
        <div class="admin-panel" id="reports-panel">
            <h2>Reportes</h2>
            <div class="reports-grid">
                <div class="report-card">
                    <h3>Ventas Mensuales</h3>
                    <div class="chart-container">
                        <canvas id="sales-chart"></canvas>
                    </div>
                </div>
                
                <div class="report-card">
                    <h3>Productos Más Vendidos</h3>
                    <div class="chart-container">
                        <canvas id="products-chart"></canvas>
                    </div>
                </div>
                
                <div class="report-card">
                    <h3>Rendimiento de Empleados</h3>
                    <div class="chart-container">
                        <canvas id="employees-chart"></canvas>
                    </div>
                </div>
                
                <div class="report-card">
                    <h3>Ingresos vs Gastos</h3>
                    <div class="chart-container">
                        <canvas id="finance-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Panel -->
        <div class="admin-panel" id="settings-panel">
            <h2>Configuración del Sistema</h2>
            
            <div class="settings-form">
                <form id="system-settings-form">
                    <div class="form-group">
                        <label for="company-name">Nombre de la Empresa</label>
                        <input type="text" id="company-name" name="company-name">
                    </div>
                    
                    <div class="form-group">
                        <label for="company-address">Dirección</label>
                        <textarea id="company-address" name="company-address"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="tax-rate">Tasa de Impuesto (%)</label>
                        <input type="number" id="tax-rate" name="tax-rate" min="0" max="100">
                    </div>
                    
                    <div class="form-group">
                        <label for="currency">Moneda</label>
                        <select id="currency" name="currency">
                            <option value="COP">Peso Colombiano (COP)</option>
                            <option value="USD">Dólar Estadounidense (USD)</option>
                            <option value="EUR">Euro (EUR)</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Opciones de Backup</label>
                        <div class="checkbox-group">
                            <input type="checkbox" id="auto-backup" name="auto-backup">
                            <label for="auto-backup">Habilitar backup automático</label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Guardar Configuración</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal templates for add/edit operations -->
<div class="modal-template" id="user-modal-template" style="display: none;">
    <!-- User add/edit form will be loaded here -->
</div>

<div class="modal-template" id="role-modal-template" style="display: none;">
    <!-- Role add/edit form will be loaded here -->
</div>

<div class="modal-template" id="product-modal-template" style="display: none;">
    <!-- Product add/edit form will be loaded here -->
</div>

<script>
    // Panel switching functionality
    document.querySelectorAll('.menu-item').forEach(item => {
        item.addEventListener('click', function() {
            // Remove active class from all menu items and panels
            document.querySelectorAll('.menu-item').forEach(i => i.classList.remove('active'));
            document.querySelectorAll('.admin-panel').forEach(p => p.classList.remove('active'));
            
            // Add active class to clicked item
            this.classList.add('active');
            
            // Show the corresponding panel
            const targetPanel = this.getAttribute('data-target');
            document.getElementById(targetPanel).classList.add('active');
        });
    });
    
    // Load initial data (users list)
    document.addEventListener('DOMContentLoaded', function() {
        // Here you would add AJAX calls to load data for each panel
        // For example, loading the users list when the page loads
    });
</script>