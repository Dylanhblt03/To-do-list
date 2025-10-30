<!-- MODALE D'AJOUT DE TÂCHE -->
<div class="modal fade" id="formulaireTache" tabindex="-1" aria-labelledby="formulaireTacheLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="/ajax/ajouterTache.ajax.php" id="ajouterTache">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="text-dark modal-title fs-5" id="formulaireTacheLabel">Ajouter une tâche au projet <?= $scrumboard['nomProjet'] ?></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-md-8">
                            <div class="form-group my-3">
                                <label for="titre">Titre</label>
                                <input required="required" type="text" name="titre" id="titre" class="form-control"/>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group my-3">
                                <label for="position">Position</label>
                                <input required="required" type="number" min="1" name="position" id="position" class="form-control"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group my-3">
                                <label for="temps_passe">Temps passé</label>
                                <input type="number" min="0" step="0.25" name="temps_passe" id="temps_passe" class="form-control" value="0"/>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group my-3">
                                <label for="estimation">Estimation</label>
                                <input type="number" min="0" step="0.25" name="estimation" id="estimation" class="form-control" value="0"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea required="required" class="form-control" placeholder="Description" id="description" name="description"></textarea>
                                <label for="description">Description</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group my-3">
                                <label for="id_etat">État de la tâche</label>
                                <select id="id_etat" name="id_etat" required="required" class="form-select">
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
                    <button type="submit"  class="btn ajouterButton" id="enregistrerTache">Enregistrer</button>
                </div>
            </div>
        </form>
    </div>
</div>