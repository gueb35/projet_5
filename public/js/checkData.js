let formFields = [
    formProdOfWeek = {
        errorLabelId : 'form_errorProdOfWeek',
        textType : [
            "form_nameProd",
            "form_saleType"
        ],
        integerType : [
            "form_quantity"
        ],
        emailType : [],
        passwordType : []
    },
    formRegistrationMembers = {
        errorLabelId : 'form_errorRegistrationMembers',
        textType : [
            "registration_members_name",
            "registration_members_firstName",
            "registration_members_town",
        ],
        pseudoType : [
            "registration_members_username",
        ],
        integerType : [],
        emailType : [
            "registration_members_email"
        ],
        passwordType : [
            "registration_members_password",
            "registration_members_confirm_password"
        ]
    },
    formLoginMember = {
        errorLabelId : 'form_errorLoginMember',
        textType : [],
        integerType : [],
        emailType : [
            "emailMemberLoginEmailType"
        ],
        passwordType : [
            "passwordMemberLoginPasswordType"
        ]
    },
    formLoginAdmin = {
        errorLabelId : 'form_errorLoginAdmin',
        textType : [],
        integerType : [],
        emailType : [
            "emailAdminLoginEmailType"
        ],
        passwordType : [
            "passwordAdminLoginPasswordType"
        ]
    },
    formRegistrationAdmin = {
        errorLabelId : 'form_errorRegistrationAdmin',
        textType : [],
        pseudoType : [
            "registration_admin_username",
        ],
        integerType : [],
        emailType : [
            "registration_admin_email"
        ],
        passwordType : [
            "registration_admin_password"
        ]
    },
]

formFields.forEach(function(form){
    let errorLabel = document.getElementById(form.errorLabelId);

    if(errorLabel){
    
        form.integerType.forEach(function(elem){
            document.getElementById(elem).addEventListener("input", function(event){
                if (isNaN(event.value)){
                    console.log("NaN " + event.value)
                    errorLabel.textContent = 'Veuillez entrer un nombre !'
                }else{
                    errorLabel.textContent = ''
                }
            })
        })

        form.textType.forEach(function(elem){
            document.getElementById(elem).addEventListener("input", function(event){
                console.log(event.srcElement.value)
                if (!event.srcElement.value.match(/^[a-zA-Z\s\-]+$/)){
                    errorLabel.textContent = 'Veuillez entrer du texte !'
                }else{
                    errorLabel.textContent = ''
                }
            })
        })

        form.pseudoType.forEach(function(elem){
            document.getElementById(elem).addEventListener("input", function(event){
                console.log(event.srcElement.value)
                if (!event.srcElement.value.match(/^[a-zA-Z\s\-\_\0-9]+$/)){
                    errorLabel.textContent = 'Veuillez entrer du texte !'
                }else{
                    errorLabel.textContent = ''
                }
            })
        })

        form.emailType.forEach(function(elem){
            document.getElementById(elem).addEventListener("input", function(event){
                if (!event.srcElement.value.match(/^[a-zA-Z\.\-\_\0-9]+@[a-zA-Z\.\-\_]+\.[a-zA-Z]{2,3}$/)){
                    errorLabel.textContent = 'Veuillez entrer un email valide !'
                }else{
                    errorLabel.textContent = ''
                }
            })
        })

        form.passwordType.forEach(function(elem){
            document.getElementById(elem).addEventListener("input", function(event){
                if (event.srcElement.value.length < 8){
                    errorLabel.textContent = 'Mot de passe de 8 caractères minimum !'
                }else{
                    errorLabel.textContent = ''
                }
            })
        })
    }
})