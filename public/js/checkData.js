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
    formRegistrationBaskComp = {
        errorLabelId : 'form_errorRegistrationBaskComp',
        textType : [
            "registration_bask_comp_name",
            "registration_bask_comp_firstName",
            "registration_bask_comp_town",
            "registration_bask_comp_username",
        ],
        integerType : [],
        emailType : [
            "registration_bask_comp_email"
        ],
        passwordType : [
            "registration_bask_comp_password",
            "registration_bask_comp_confirm_password"
        ]
    },
    formRegistrationBaskColl = {
        errorLabelId : 'form_errorRegistrationBaskColl',
        textType : [
            "registration_bask_coll_name",
            "registration_bask_coll_firstName",
            "registration_bask_coll_town",
            "registration_bask_coll_dayOfWeek",
            "registration_bask_coll_username"
        ],
        integerType : [],
        emailType : [
            "registration_bask_coll_email"
        ],
        passwordType : [
            "registration_bask_coll_password",
            "registration_bask_coll_confirm_password"
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
        textType : [
            "registration_admin_username"
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
                if (!event.srcElement.value.match(/^[a-zA-Z\s]+$/)){
                    errorLabel.textContent = 'Veuillez entrer du texte !'
                }else{
                    errorLabel.textContent = ''
                }
            })
        })

        form.emailType.forEach(function(elem){
            document.getElementById(elem).addEventListener("input", function(event){
                if (!event.srcElement.value.match(/^[a-zA-Z\.\-\_]+@[a-zA-Z\.\-\_]+\.[a-zA-Z]{2,3}$/)){
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