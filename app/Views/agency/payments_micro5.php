                            <td>
                                <a href="<?= base_url('agency/payment-detail/'.$p['id']) ?>" class="btn btn-primary btn-sm rounded-pill px-3">
                                    <i class="bi bi-plus-circle me-1"></i> Cicilan Baru
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
