 /*récupération des emplacements*/
 /*titres seul*/
 var title1 = document.getElementById("title1")
 var title1bis = document.getElementById("title1bis")
 var title2 = document.getElementById("title2")
 var title2bis = document.getElementById("title2bis")
 var title3 = document.getElementById("title3")
 var title3bis = document.getElementById("title3bis")
 var title4 = document.getElementById("title4")
 var title4bis = document.getElementById("title4bis")
 var title5 = document.getElementById("title5")
 var title5bis = document.getElementById("title5bis")
 var title6 = document.getElementById("title6")
 var title6bis = document.getElementById("title6bis")

 /*conteneur : titres, images et contenus*/
 var story = document.getElementById("story")
 story.style.display = "none"
 var prod = document.getElementById("prod")
 prod.style.display = "none"
 var activity = document.getElementById("activity")
 activity.style.display = "none"
 var fonction = document.getElementById("function")
 fonction.style.display = "none"
 var find = document.getElementById("find")
 find.style.display = "none"
 var certif = document.getElementById("certif")
 certif.style.display = "none"


var Titleshow ={
    event: function(){
        title1.addEventListener("click", function(){
            title1.style.display = "none"
            story.style.display = ""
        })
        title1bis.addEventListener("click", function(){
            title1.style.display = ""
            story.style.display = "none"    
        })


        title2.addEventListener("click", function(){
            title2.style.display = "none"
            prod.style.display = ""
        })
        title2bis.addEventListener("click", function(){
            title2.style.display = ""
            prod.style.display = "none"    
        })


        title3.addEventListener("click", function(){
            title3.style.display = "none"
            activity.style.display = ""    
        })
        title3bis.addEventListener("click", function(){
            title3.style.display = ""
            activity.style.display = "none"    
        })

        title4.addEventListener("click", function(){
            title4.style.display = "none"
            fonction.style.display = ""    
        })
        title4bis.addEventListener("click", function(){
            title4.style.display = ""
            fonction.style.display = "none"    
        })

        title5.addEventListener("click", function(){
            title5.style.display = "none"
            find.style.display = ""    
        })
        title5bis.addEventListener("click", function(){
            title5.style.display = ""
            find.style.display = "none"    
        })

        title6.addEventListener("click", function(){
            title6.style.display = "none"
            certif.style.display = ""    
        })
        title6bis.addEventListener("click", function(){
            title6.style.display = ""
            certif.style.display = "none"    
        })
    }
}
var myTitleshow = Object.create(Titleshow)
myTitleshow.event()