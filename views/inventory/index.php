<?php
$pageTitle = 'Blood Inventory Management';
require_once __DIR__ . '/../layout/header.php';

// Safety fallbacks
$inventory  = $inventory ?? [];
$totalUnits = $totalUnits ?? 0;
?>

<div class="dashboard-container">
    <?php require_once __DIR__ . '/../layout/sidebar.php'; ?>

    <div class="main-content">
        <header style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
            <div>
                <h2 class="text-gradient">Blood Inventory</h2>
                <p style="color:var(--text-muted)">Manage available blood stocks</p>
            </div>
            <div class="glass-panel" style="padding:0.5rem 1.5rem;">
                <span style="color:var(--text-muted);margin-right:8px;">Total Units:</span>
                <strong style="font-size:1.2rem;color:var(--primary-color);"><?= (int)$totalUnits ?></strong>
            </div>
        </header>

        <!-- Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Inventory Grid -->
        <div class="stats-grid" style="grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.5rem;">
            <?php if (!empty($inventory)): ?>
                <?php foreach ($inventory as $item): ?>
                    <?php 
                        $units = (int)$item['units'];
                        $isLow = $units < 5;
                        $borderColor = $isLow ? 'rgba(239, 68, 68, 0.4)' : 'rgba(34, 197, 94, 0.3)';
                        $shadow      = $isLow ? '0 0 15px rgba(239, 68, 68, 0.15)' : 'none';
                    ?>
                    
                    <div class="glass-panel stat-card" style="
                        border: 1px solid <?= $borderColor ?>;
                        box-shadow: <?= $shadow ?>;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        padding: 1.5rem;
                        transition: transform 0.2s;
                    " onmouseover="this.style.transform='translateY(-5px)'" 
                       onmouseout="this.style.transform='translateY(0)'">
                        
                        <!-- Blood Group Circle -->
                        <div style="
                            width: 60px;
                            height: 60px;
                            border-radius: 50%;
                            background: rgba(239, 68, 68, 0.1);
                            color: #ef4444;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-size: 1.5rem;
                            font-weight: 800;
                            margin-bottom: 1rem;
                            border: 2px solid rgba(239, 68, 68, 0.2);
                        ">
                            <?= htmlspecialchars($item['blood_group']) ?>
                        </div>

                        <!-- Units -->
                        <div style="font-size: 2rem; font-weight: 700; color: white; line-height: 1;">
                            <?= $units ?>
                        </div>
                        <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.5rem;">
                            Units Available
                        </div>

                        <!-- Action Button -->
                        <button class="btn btn-primary w-100" 
                                onclick="openStockModal('<?= $item['blood_group'] ?>')"
                                style="font-size: 0.9rem; padding: 0.5rem;">
                            ➕ Increase
                        </button>
                        
                    </div>

                <?php endforeach; ?>
            <?php else: ?>
                <div class="glass-panel" style="grid-column: 1 / -1; text-align: center; color: var(--text-muted);">
                    No inventory data found.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add Stock Modal -->
<div id="stockModal" class="modal-overlay" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:1000;align-items:center;justify-content:center;backdrop-filter:blur(5px);">
    <div class="glass-panel" style="width:400px;max-width:90%;position:relative;animation: modalFadeIn 0.3s ease;">
        <h3 class="mb-4 text-center">Update Inventory</h3>
        
        <form action="<?= BASE_URL ?>/inventory/update" method="POST">
            <input type="hidden" name="blood_group" id="modalGroup">
            <input type="hidden" name="operation" value="add">

            <div class="mb-4 text-center p-3" style="background:rgba(255,255,255,0.03); border-radius:12px;">
                <span style="color:var(--text-muted); font-size:0.9rem;">Adding units to group</span>
                <div id="displayGroup" style="font-size:3rem;font-weight:bold;color:#ef4444;margin-top:5px;text-shadow:0 0 20px rgba(239,68,68,0.3);">
                    A+
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Units to Add</label>
                <div style="position:relative;">
                    <input type="number" name="units" class="form-control" min="1" value="1" required style="font-size:1.2rem; text-align:center; padding-left: 10px;">
                    <span style="position:absolute; right:15px; top:50%; transform:translateY(-50%); color:var(--text-muted);">ml/units</span>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary w-100 py-2">Confirm Add</button>
                <button type="button" class="btn btn-outline w-100 py-2" onclick="closeStockModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<style>
@keyframes modalFadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<script>
function openStockModal(group) {
    document.getElementById('modalGroup').value = group;
    document.getElementById('displayGroup').innerText = group;
    document.getElementById('stockModal').style.display = 'flex';
}

function closeStockModal() {
    document.getElementById('stockModal').style.display = 'none';
}

// Close on outside click
document.getElementById('stockModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeStockModal();
    }
});
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
