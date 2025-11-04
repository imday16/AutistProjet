 const stimsData = [
            {
                id: 1,
                title: "Flapping des mains",
                explanation: "Le 'flapping' des mains est un mouvement répétitif et rythmique des mains ou des bras. Il est souvent associé à une excitation intense (positive ou négative) ou à une surcharge sensorielle. C'est une forme d'auto-régulation qui aide l'enfant à gérer ses émotions et à filtrer les stimuli externes.",
                suggestions: [
                    "Proposer des alternatives motrices : balles anti-stress, fidget toys, élastiques à mâcher.",
                    "Identifier le déclencheur : Est-ce l'excitation, l'anxiété, ou l'ennui ? Adapter l'environnement en conséquence.",
                    "Utiliser des activités de pression profonde : serrer un coussin, faire des 'sandwichs' corporels (pression douce).",
                    "Encourager le mouvement dans un cadre structuré : sauts sur trampoline, balançoire, ou danse courte.",
                    "Créer un 'coin calme' où le flapping est autorisé sans jugement pour l'auto-régulation."
                ]
            },
            {
                id: 2,
                title: "Balancement du corps",
                explanation: "Le balancement d'avant en arrière ou de gauche à droite est un geste auto-stimulant qui procure un sentiment de sécurité et de calme. Il est souvent utilisé pour se réconforter ou pour bloquer les stimuli externes accablants.",
                suggestions: [
                    "Fournir des sièges qui permettent le mouvement (fauteuil à bascule, ballon d'exercice).",
                    "Intégrer des pauses de mouvement régulières dans la routine de l'enfant.",
                    "Utiliser des couvertures lestées ou des gilets de compression pour un apport sensoriel profond.",
                    "Enseigner des techniques de respiration ou de relaxation comme alternative au balancement."
                ]
            },
            {
                id: 3,
                title: "Frottement des objets",
                explanation: "Frotter des objets (tissus, ficelles, jouets) contre la peau ou entre les doigts est une recherche de stimulation tactile. Cela peut être apaisant et aider à la concentration.",
                suggestions: [
                    "Mettre à disposition une boîte sensorielle avec des textures variées (soie, velours, brosse douce).",
                    "Remplacer les objets inappropriés par des 'objets de confort' désignés (doudou texturé, petit carré de tissu doux).",
                    "Intégrer des activités tactiles dans la journée : pâte à modeler, sable cinétique, jeux d'eau."
                ]
            },
            {
                id: 4,
                title: "Fixation visuelle",
                explanation: "Regarder intensément des motifs, des lumières clignotantes, ou faire tourner des objets pour observer le mouvement. C'est une recherche de stimulation visuelle intense ou, au contraire, une tentative de simplifier un environnement visuel trop complexe.",
                suggestions: [
                    "Proposer des jouets visuels appropriés : toupies, bulles, lampes à fibres optiques.",
                    "Réduire l'encombrement visuel dans l'environnement de travail ou de jeu.",
                    "Utiliser des filtres de lumière ou des lunettes de soleil si la lumière vive est le déclencheur.",
                    "Encourager le suivi visuel structuré (puzzles, jeux de recherche)."
                ]
            },
            {
                id: 5,
                title: "Tapoter des doigts",
                explanation: "Taper ou frapper des doigts de manière répétitive sur une surface. Ce geste fournit un retour auditif et tactile, aidant à la concentration ou à l'évacuation d'un surplus d'énergie.",
                suggestions: [
                    "Fournir un instrument de percussion discret (petit tambourin de doigt, clavier silencieux).",
                    "Utiliser des surfaces qui absorbent le son (tapis de souris épais, table en bois).",
                    "Proposer des activités qui canalisent le rythme : jouer du piano, taper des mains sur une chanson."
                ]
            }
            // Ajoutez d'autres données ici pour simuler une liste plus longue
        ];

        const stimsList = document.getElementById('stims-list');
        const detailsPanel = document.getElementById('details-panel');
        const stimTitle = document.getElementById('stim-title');
        const stimExplanation = document.getElementById('stim-explanation');
        const stimSuggestions = document.getElementById('stim-suggestions');

        // Fonction pour mettre à jour le panneau de détails
        function updateDetails(stim) {
            stimTitle.textContent = stim.title;
            stimExplanation.textContent = stim.explanation;
            
            // Mise à jour des suggestions
            stimSuggestions.innerHTML = '';
            stim.suggestions.forEach(suggestion => {
                const li = document.createElement('li');
                li.textContent = suggestion;
                stimSuggestions.appendChild(li);
            });
        }

        // Fonction pour générer la liste complète des gestes (pour simuler les "centaines")
        function generateStimsList() {
            stimsList.innerHTML = ''; // Vider la liste existante
            stimsData.forEach(stim => {
                const li = document.createElement('li');
                li.setAttribute('data-stim-id', stim.id);
                li.textContent = stim.title;
                
                // Gérer le clic sur un élément de la liste
                li.addEventListener('click', () => {
                    // Mettre à jour les classes actives
                    document.querySelectorAll('.stims-list li').forEach(item => {
                        item.classList.remove('active');
                    });
                    li.classList.add('active');
                    
                    // Mettre à jour le panneau de détails
                    updateDetails(stim);
                });
                
                stimsList.appendChild(li);
            });
            
            // Initialiser le premier élément comme actif et afficher ses détails
            if (stimsData.length > 0) {
                const firstItem = stimsList.querySelector(`[data-stim-id="${stimsData[0].id}"]`);
                if (firstItem) {
                    firstItem.classList.add('active');
                    updateDetails(stimsData[0]);
                }
            }
        }

        // Exécuter la génération de la liste au chargement de la page
        document.addEventListener('DOMContentLoaded', generateStimsList);