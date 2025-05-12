Ce fichier Readme regroupe la conception de l’interface utilisateur pour CongéFacile. Nous prenons comme exmple d'entreprise MentalWorks. Le but est de faire une application de gestion des congés dédiée à un projet scolaire, qui peut etre utilisé en entreprise. L’objectif est de proposer une interface claire, moderne et intuitive permettant aux utilisateurs de gérer leurs demandes de congés, de suivre leur solde et d’interagir avec les responsables RH oumanagers.

L’interface est adaptée à des besoins pédagogiques. Ce fichier semble être structuré pour présenter un parcours utilisateur complet, de la connexion jusqu’à la gestion et la consultation des congés.
Le site a 2 cotés, un coté collaborateur/employer et un coté manager.

Pour mieux comprendre cela voici une arborescence :

![image](https://github.com/user-attachments/assets/5ef71b40-9275-4353-b44f-3858c0141b29)

Voici le schéma relationnel de notre base de donnée : 

![image](https://github.com/user-attachments/assets/a680f507-7598-4e24-a934-4e8cebcc9dc2)

Il faut aussi savoir qu'un service appartient à un collaborateur et un manager doit gérer un service.

![image](https://github.com/user-attachments/assets/4d4205b6-228e-46ed-b470-eeacf04ef69c)


Tout d'abord il y a une page commune qui nous permet d'etre connecté au site :

Écran de connexion simple avec email/mot de passe. Avec la possibilité d'accéder a la page "mot de passe oublié".
![image](https://github.com/user-attachments/assets/de603b89-f0fd-4dff-b54e-8cfef4343652)

La page mot de passe oublié permet de réinitialiser son mot de passe. Pour cela il faut renseigner son adresse mail, puis nous recevons un mail qui contient un lien pour réinitialiser son mot de passe.
![image](https://github.com/user-attachments/assets/d35f4c09-082f-4659-9ae2-60ec90a828c2)

Et ici nous retrouvons la page d'accueil qui est commune a chaque coté (manager/collaborateur), seulement le menu de gauche change.
![image](https://github.com/user-attachments/assets/d364ed27-d3fd-4483-a368-6837f6f5e5c3)

**Coté Collaborateur :**

**En tant que collaborateur,** nous retrouvons un formulaire permettant à l'utilisateur de soumettre une nouvelle demande de congé. L’interface propose une sélection de dates, motifs, et type de congé.:
![image](https://github.com/user-attachments/assets/89113b1f-9f2b-430c-b134-3d4c5cf33812)

Vue listant les demandes passées, avec leur statut (en attente, validée, refusée). Interface en tableau ou liste, avec tri possible par date ou statut. :            
![image](https://github.com/user-attachments/assets/fc40d522-6fc0-40e3-8c5c-b8d4b6a86a62)

Voir les détails de nos demandes de congés :                  
![image](https://github.com/user-attachments/assets/ff4527db-a292-4d3f-8d5c-721e5b18bf16)

Page dédiée aux informations personnelles du collaborateur : nom, email, poste, etc. Possibilité de modifier certaines données ou changer le mot de passe. :                                         
![image](https://github.com/user-attachments/assets/c485d532-c2a1-4e31-a393-aa5479aa7be5)

Permet de personnalisez ses préférences et de les enregistrer : **(page non obligatoire)**
![image](https://github.com/user-attachments/assets/9d1345b6-d25e-44d5-bc2a-d5421f43d740)

**Coté Manager :**

**En tant que manager,** je peux voir les demandes en attentes de le nombre de demandes en attente. Il y a une liste des congés soumis par les employés en attente de validation. Affichage sous forme de tableau avec filtre par date, type de congé ou employé. Actions rapides disponibles : "Valider", "Refuser", et si il n'y a pas dde réponse c'est "En cours" :

![image](https://github.com/user-attachments/assets/da072f2f-38c8-4c12-87f5-8db5eb03f973)

Page dédiée à une demande spécifique. Affiche les dates demandées, le type de congé, le commentaire éventuel de l’employé, et le solde disponible. Options pour valider ou refuser avec commentaire.:                                   
![image](https://github.com/user-attachments/assets/11cfcd86-2ce8-485e-85bc-2284678533cd)

Voir l'historique des demandes déjà traitée :               
![image](https://github.com/user-attachments/assets/24a2294d-0c38-478b-a869-df53367b28af)

Page dédiée aux informations personnelles du manager : nom, email, poste, etc. Possibilité de modifier certaines données ou changer le mot de passe :                                         :                                                    
![image](https://github.com/user-attachments/assets/12b1333c-e19b-4e5b-8d76-831bba928bd4)

Permet de personnalisez ses préférences et de les enregistrer : **(page non obligatoire)**
![image](https://github.com/user-attachments/assets/2a0b4088-c9c2-4765-9808-123aaf728f96)

Vu des statistiques des congés au sein de l'ntreprise : **(page non obligatoire)**

![image](https://github.com/user-attachments/assets/45c90a00-cbf6-41b0-8be2-29ca7a0765e6)


Ajoutez une type de demande de congé, voir les détails des demandes de congés(permet aussi de les supprimer ou de modifier le nombre de type de demande de congé et le nom du type de la demande de congé) :                                         
![image](https://github.com/user-attachments/assets/1751a1e8-3474-434d-adc9-f21128b43e7e)

Voir les différents types et leurs nombres au sein de l'entreprise :       
![image](https://github.com/user-attachments/assets/21abf445-ba5c-4f9f-babe-f7cd31c0a53e)

Ajouter un poste  voir les détails des postes (permet aussi de les supprimer ou de modifier le nombre de poste et le nom du type du poste) :                                       
![image](https://github.com/user-attachments/assets/9d4f2b88-4280-4b48-9efd-c815a7fc20ca)

Voir les différents postes et leurs nombres au sein de l'entreprise :
![image](https://github.com/user-attachments/assets/4ac5e034-9ca9-4a50-98f6-548440311029)

Renseigne les directions et services au sein de l'entreprise : **(page non obligatoire)**
![image](https://github.com/user-attachments/assets/b2e21b9a-65bf-4eec-863d-f19aa78aebe5)

Permet de supprimer une case ou de modifier une case : **(page non obligatoire)**
![image](https://github.com/user-attachments/assets/437cc6c2-1dae-4d4e-bd44-7c4ba68a66ba)

Renseigne des informations sur les managers (leurs noms, norms de familles, et leurs directions/services) : **(page non obligatoire)**
![image](https://github.com/user-attachments/assets/6e2e30b7-9cd3-42a9-9b5c-69eb5f430e3b)

Avoir la possibilité de modifier les informations des managers et de les mettres a jours : **(page non obligatoire)**
![image](https://github.com/user-attachments/assets/ac83e2e4-4e5a-4ffc-984c-3b011357f7a7)





