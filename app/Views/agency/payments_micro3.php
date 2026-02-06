                    <?php if(empty($participants)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="py-4">
                                    <i class="bi bi-wallet2 text-muted fs-1 opacity-25 mb-3 d-block"></i>
                                    <p class="text-secondary mb-0">Belum ada data pembayaran jamaah.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($participants as $p): ?>
                        <tr>
                            <td class="ps-4">
