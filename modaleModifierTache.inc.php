<!-- MODALE DE MODIFICATION DE TÂCHE -->
<div class="modal fade" id="modalModifierTache" tabindex="-1" aria-labelledby="modifierTacheLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="/ajax/modifierTache.ajax.php" id="modifierTache">
            <input type="hidden" name="id" id="id"/>
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="text-dark modal-title fs-5" id="modifierTacheLabel">Modifier une tâche</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-md-8">
                            <div class="form-group my-3">
                                <label for="titreModification">Titre</label>
                                <input required="required" type="text" name="titre" id="titreModification" class="form-control"/>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group my-3">
                                <label for="positionModification">Position</label>
                                <input required="required" type="number" min="1" name="position" id="positionModification" class="form-control"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group my-3">
                                <label for="temps_passeModification">Temps passé</label>
                                <input type="number" min="0" step="0.25" name="temps_passe" id="temps_passeModification" class="form-control" value="0"/>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group my-3">
                                <label for="estimationModification">Estimation</label>
                                <input type="number" min="0" step="0.25" name="estimation" id="estimationModification" class="form-control" value="0"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea required="required" class="form-control" placeholder="Description" id="descriptionModification" name="description"></textarea>
                                <label for="descriptionModification">Description</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group my-3">
                                <label for="id_etatModification">État de la tâche</label>
                                <select id="id_etatModification" name="id_etat" required="required" class="form-select">
                                    <?php
                                    foreach($listeEtats as $etat) {
                                        ?>
                                        <option value="<?= $etat['id'] ?>" ><?= $etat['libelle'] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn ajouterButton" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit"  class="btn ajouterButton" id="boutonModifierTache">Enregistrer</button>
                </div>
            </div>
        </form>
    </div>
</div>