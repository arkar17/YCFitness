// $(document).ready(function(){
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

        var allData = {

        }

        var currentTab = 0; // Current tab is set to be the first tab (0)
        showTab(currentTab); // Display the current tab

        $(".hip-tooltip").hide()

        function checkFemale(gender){
            console.log(gender.value)
            const hip = `<input  type="number" required class="customer-registeration-input" placeholder="Hip" name="hip">`
            // const toolTip = `<iconify-icon  icon="ant-design:exclamation-circle-outlined" class="description-icon hip-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Stand with your feet directly beneath your hips and wrap the tape around the widest part of your hips and buttocks."></iconify-icon>`
            const hipNode = document.querySelector('[name="hip"]')
            // const toolTipNode = document.querySelector('.hip-tooltip')
            const parent = document.querySelector("#parent-hip")
            if(gender.value === "female"){
                parent.insertAdjacentHTML("afterbegin",hip)
                $(".hip-tooltip").show()
                // parent.insertAdjacentHTML("afterbegin",toolTip)
                // console.log(hip)
            }else{
                if(hipNode){
                    parent.removeChild(hipNode);
                    $(".hip-tooltip").hide()
                    // parent.removeChild(toolTipNode)
                }

            }
        }

        $("[name='weight'] , [name='idealWeightInput']").change(function(){
            if($("[name='weight']").val() !== "" && $("[name='idealWeightInput']").val() !== ""){
                const idealWeight = $("[name='idealWeightInput']").val()
                const weight = $("[name='weight']").val()

                const weightDiff = parseInt(idealWeight) - parseInt(weight)
                var duration
                // console.log(weightDiff.toString())
                if(weightDiff === 0){
                    Swal.fire({
                        text: "Current weight and ideal weight cannot be equal",
                        confirmButtonColor: '#3CDD57',
                        timer: 3000
                      });
                    //alert("Current weight and ideal weight cannot be equal")

                }

                if(weightDiff < 0){
                    if(Math.abs(weightDiff).toString().length ===1){
                        // console.log("weight diff 1")
                        duration = "1"
                    }
                    if(Math.abs(weightDiff).toString().length >= 2){
                        // console.log("weight diff 2")
                        if(Math.abs(weightDiff) >= 80){
                            duration = 12
                        }else{
                            if(Math.abs(weightDiff).toString().split("")[1] === '0'){
                                duration  = Math.abs(weightDiff).toString().split("")[0]
                            }else{
                                duration  = (parseInt(Math.abs(weightDiff).toString().split("")[0]) + 1).toString()
                            }
                        }

                    }

                    if(parseInt(duration) === 1){
                        $(".weight-difference-text").text(`Losing ${Math.abs(weightDiff)} lb takes about ${duration} month.`)
                    }else{
                        $(".weight-difference-text").text(`Losing ${Math.abs(weightDiff)} lb takes about ${duration} months.`)
                    }

                }
                if(weightDiff > 0){

                    duration = (Math.round(parseInt(Math.abs(weightDiff)) / 5)).toString()

                    if(parseInt(duration) === 1 || parseInt(duration) === 0){
                        $(".weight-difference-text").text(`Gaining ${Math.abs(weightDiff)} lb takes about ${duration === 0 ? duration : "1"} month.`)
                    }else{
                        $(".weight-difference-text").text(`Gaining ${Math.abs(weightDiff)} lb takes about ${duration} months.`)
                    }


                }

            }
        })

        function checkedOnClick(el, category){

          // Select all checkboxes by class
          if (category === 'preferedActivities'){
            var checkboxesList = document.getElementsByClassName("physical-limit-checkbox");
            for (var i = 0; i < checkboxesList.length; i++) {
              checkboxesList.item(i).checked = false; // Uncheck all checkboxes
            }
          }

          if(category === "bodyType"){
            var bodyTypeCheckboxesList = document.getElementsByName("bodyType");
              for(var i = 0; i < bodyTypeCheckboxesList.length; i++){
              bodyTypeCheckboxesList.item(i).checked = false
            }
          }

          if(category === 'mainGoal'){
            var mainGoalCheckboxesList = document.getElementsByName("mainGoal");
            for(var i = 0; i < mainGoalCheckboxesList.length; i++){
              mainGoalCheckboxesList.item(i).checked = false
            }
          }

          if(category === 'typicalDay'){
            var typicalDayCheckboxesList = document.getElementsByName("typicalDay");
            for(var i = 0; i < typicalDayCheckboxesList.length;i++){
              typicalDayCheckboxesList.item(i).checked = false
            }
          }

          if(category === 'sleep'){
            var sleepCheckboxesList = document.getElementsByName("sleep");
            for(var i = 0; i < sleepCheckboxesList.length;i++){
                sleepCheckboxesList.item(i).checked = false
            }
          }

          if(category === "energyLevel"){
            var energyLevelCheckboxesList = document.getElementsByName("energyLevel");
            for(var i = 0; i < energyLevelCheckboxesList.length;i++){
                energyLevelCheckboxesList.item(i).checked = false
            }
          }

          if(category === "idealWeight"){
            var idealWeightCheckboxesList = document.getElementsByName("idealWeight");
            for(var i = 0; i < idealWeightCheckboxesList.length;i++){
                idealWeightCheckboxesList.item(i).checked = false
            }
          }

          if(category === 'physicalActivity'){
            var physicalActivityCheckboxesList = document.getElementsByName("physicalActivity");
            for(var i = 0; i < physicalActivityCheckboxesList.length;i++){
                physicalActivityCheckboxesList.item(i).checked = false
            }
          }
          if(category === 'waterIntake'){
            var waterIntakeCheckboxesList = document.getElementsByName("waterIntake");
            for(var i = 0; i < waterIntakeCheckboxesList.length;i++){
                waterIntakeCheckboxesList.item(i).checked = false
            }
          }
          if(category === 'memberPlan'){
            var memberPlanCheckboxesList = document.getElementsByName("memberPlan");
            for(var i = 0; i < memberPlanCheckboxesList.length;i++){
                memberPlanCheckboxesList.item(i).checked = false
            }
          }
          if(category === 'proficiency'){
            var proficiencyCheckboxesList = document.getElementsByName("proficiency");
            for(var i = 0; i < proficiencyCheckboxesList.length;i++){
                proficiencyCheckboxesList.item(i).checked = false
            }
          }
          if(category === 'diet'){
            var dietCheckboxesList = document.getElementsByName("diet");
            for(var i = 0; i < dietCheckboxesList.length;i++){
                dietCheckboxesList.item(i).checked = false
            }
          }

          if(el.checked){
            el.checked = false;
          }else{
            el.checked = true;
          }

        }

        function showTab(n) {
            // This function will display the specified tab of the form ...
            var x = document.getElementsByClassName("tab");
            x[n].style.display = "block";
            // ... and fix the Previous/Next buttons:
            if (n == 0) {
              document.getElementById("prevBtn").style.display = "none";
            } else {
              document.getElementById("prevBtn").style.display = "inline";
            }
            if (n == (x.length - 1)) {
              document.getElementById("nextBtn").innerHTML = "Submit";
            } else {
              document.getElementById("nextBtn").innerHTML = "Next";
            }
            // ... and run a function that displays the correct step indicator:
          //   fixStepIndicator(n)
        }

        function nextPrev(n, category) {
            // This function will figure out which tab to display
            var x = document.getElementsByClassName("tab");
            // Exit the function if any field in the current tab is invalid:
            if (n == 1 && !validateForm(category)) return false;
            // Hide the current tab:
            x[currentTab].style.display = "none";
            // Increase or decrease the current tab by 1:
            currentTab = currentTab + n;
            // if you have reached the end of the form... :
            if (currentTab >= x.length) {
              //...the form gets submitted:
            //   document.getElementById("regForm").submit();
              return false;
            }

            if(currentTab < 0){
                currentTab = 0
            }
            // Otherwise, display the correct tab:
            showTab(currentTab);
          }

        function validateForm(category) {
          // This function deals with validation of the form fields
          var x, y, i, valid = true;
          // var gender = document.querySelector('[name="customerGender"]')




          x = document.getElementsByClassName("tab");
          y = x[currentTab].querySelectorAll("input[type='text'],input[type='number'],input[type='email'],input[type='password'], select");

          // A loop that checks every input field in the current tab:
          for (i = 0; i < y.length; i++) {
            // If a field is empty...
            if (y[i].value == "") {
              // add an "invalid" class to the field:
              y[i].className += " invalid";
              // and set the current valid status to false:
              valid = false;
            }else{
                y[i].classList.remove("invalid")
            }

          }
          // check if passwords are equal
          if(category === "personalInfo"){
            var password = document.querySelector('[name="password"]')
            var confirmPassword = document.querySelector('[name="confirmPassword"]')
            var phone = document.querySelector('[name="phone"]')
            var email = document.querySelector('[name="email"]')
            var name = document.querySelector('[name= "name"]')
            var address = document.querySelector("[name='address']")
            var personalInfoData = []
            if(password.value !== confirmPassword.value){
              valid = false
              password.classList.add("invalid")
              confirmPassword.classList.add("invalid")

              Swal.fire({
                text: "Passwords are not equal",
                confirmButtonColor: '#3CDD57',
                timer: 3000
              });
              //alert("Passwords are not equal")
            }

            if(password.value !== "" && !(password.value.length >= 6 && password.value.length <= 11) ){
              valid = false
              password.classList.add("invalid")
              confirmPassword.classList.add("invalid")
              Swal.fire({
                text: "Passwords should have 6 to 11 characters or numbers.",
                confirmButtonColor: '#3CDD57',
                timer: 3000
              });
              //alert("Passwords should have 6 to 11 characters or numbers.")
            }

            if(phone.value !== "" && !(phone.value.length >= 7 && phone.value.length <= 11)){
              valid = false
              phone.classList.add("invalid")
              //Swal.fire("Phone number should have 7 to 11 numbers");
              Swal.fire({
                text: "Phone number should have 7 to 11 numbers",
                confirmButtonColor: '#3CDD57',
                timer: 3000
              });

              //alert("Phone number should have 7 to 11 numbers")
            }if(phone.value == "")
            {
                valid = false
                phone.classList.add("invalid")
                Swal.fire({
                    text: "Required fields are empty.",
                    confirmButtonColor: '#3CDD57',
                    timer: 3000
                  });
                }else{
                var cus_phone = $("#phone").val();
                $.ajax({
                        url : 'checkPhone',
                        method: 'get',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data:  {"phone":cus_phone},
                        success   : function(data) {
                            if(data.status == 300){
                                Swal.fire({
                                    text: data.message,
                                    confirmButtonColor: '#3CDD57',
                                    timer: 3000
                                  });

                                valid = false;
                                $( "#phone" ).addClass("invalid");
                                nextPrev(-1)

                            }
                            if(data.status == 200){

                                // alert(data.message);
                            }
                        },
                });
            }

            let regex = new RegExp('[a-z0-9]+@[a-z]+\.[a-z]{2,3}');

            if(email.value !== "" && regex.test(email.value) === false){
              valid = false
              email.classList.add("invalid")
              Swal.fire({
                text: "Email is not valid.",
                confirmButtonColor: '#3CDD57',
                timer: 3000
              });
              //alert("Email is not valid.")
            }if(email.value == "")
            {
                valid = false
                email.classList.add("invalid")
                Swal.fire({
                    text: "Required fields are empty.",
                    confirmButtonColor: '#3CDD57',
                    timer: 3000
                  });
            }else{
                var cus_email = $("#email").val();
                $.ajax({
                        url : 'checkemail',
                        method: 'get',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data:  {"email":cus_email},
                        success   : function(data) {
                            if(data.status == 300){
                                Swal.fire({
                                    text: data.message,
                                    confirmButtonColor: '#3CDD57',
                                    timer: 3000
                                  });
                                // alert(data.message);
                                valid = false;
                                $( "#email" ).addClass("invalid");
                                 nextPrev(-1)

                            }
                            if(data.status == 200){

                                // alert(data.message);
                            }
                        },
                });
            }

            if(valid){
                personalInfoData.push(name.value,phone.value,email.value,address.value,password.value,confirmPassword.value)
                allData = {
                    ...allData,
                    personalInfo : personalInfoData
                }
            }
          }


          if(category === "bodyMeasurements"){
            var feet = document.querySelector('[name="feet"]')
            var inches = document.querySelector('[name="inches"]')
            // var weight = document.querySelector('[name="weight"]')
            // var idealWeight = document.querySelector('[name="idealWeight"]')
            var age = document.querySelector('[name="age"]')
            var request_type = document.querySelector("[name='request_type']")
            var gender = document.querySelector('[name="gender"]')
            var neck = document.querySelector('[name="neck"]')
            var waist = document.querySelector('[name="waist"]')
            var hip = document.querySelector('[name="hip"]')
            var shoulders = document.querySelector('[name="shoulders"]')
            var bodyMeasurementsData

            if(valid){
            //   console.log(feet,inches,weight,age,gender,neck,waist,hip,shoulders)
                const overallInches = parseInt((feet.value * 12)) + parseInt(inches.value)
                // const bmi = (parseInt(weight.value)/(overallInches*overallInches))*703
                // var bfp
                // console.log(bmi)
                // console.log((parseInt(waist)))
                // const bfp = ((86.010*Math.log10(parseInt(waist.value)-parseInt(neck.value)))-(70.041*(Math.log10(overallInches))))+36.76
                if(gender.value === "male"){
                    console.log("male bfp")
                //   bfp= Math.round((86.010*(Math.log(waist.value*1-neck.value*1)/Math.log(10))-70.041*(Math.log(overallInches)/Math.log(10))+36.76*1)*100)/100;
                }
                if(gender.value === "female"){
                    console.log("female bfp")
                //   bfp = Math.round((163.205*(Math.log(waist.value*1.0+hip.value*1.0-neck.value*1.0)/Math.log(10))- 97.684*(Math.log(overallInches)/Math.log(10))-78.387*1.0)*100)/100;
                }

                // console.log(bfp)
                bodyMeasurementsData = {
                  height : overallInches,
                  age : age.value,
                  gender : gender.value,
                  neck : neck.value,
                  waist : waist.value,
                  hip : hip?.value,
                  shoulders : shoulders.value,
                  request_type:request_type.value,
                //   bmi : bmi,
                //   bfp : bfp,
                }
              allData = {
                ...allData,
                bodyMeasurements  : bodyMeasurementsData
            }
            }
          }
          //check if user selects physical limitations or not(must select at least one)
          if(category === "physicalLimitations"){
            var physicalLimitations = document.querySelectorAll("input[name='physicalLimitations']:checked")
            var physicalLimitationsArr = []
            var physicalLimitationsData = []

            for(i = 0; i < physicalLimitations.length;i++){
              physicalLimitationsArr.push(physicalLimitations[i].value)
            }

            if(physicalLimitationsArr.length === 0){
              valid = false
              Swal.fire({
                text: "Please Select at least one.",
                confirmButtonColor: '#3CDD57',
                timer: 3000
              });

            }

            if(valid){
              for(i = 0; i < physicalLimitations.length;i++){
                physicalLimitationsData.push(physicalLimitationsArr[i])
              }

              allData = {
                ...allData,
                physicalLimitations  : physicalLimitationsData
            }
            }
          }

          //check if user selects prefered activities or not
          if( category === "preferedActivities"){
            var preferedActivities = document.querySelectorAll("input[name='preferedActivities']:checked")
            var preferedActivitiesArr = []
            var preferedActivitiesData = []

            for(i = 0; i < preferedActivities.length;i++){
              preferedActivitiesArr.push(preferedActivities[i].value)
            }

            if(preferedActivitiesArr.length === 0){
              valid = false
              Swal.fire({
                text: "Please Select at least one.",
                confirmButtonColor: '#3CDD57',
                timer: 3000
              });
              //alert("Please Select at least one.")
            }

            if(valid){
              for(i = 0; i < preferedActivities.length;i++){
                preferedActivitiesData.push(preferedActivitiesArr[i])
              }
              allData = {
                ...allData,
                preferedActivities  : preferedActivitiesData
            }
            }
          }

          //check is user selects body type
          if(category === "bodyType"){
            var bodyType = document.querySelectorAll("input[name='bodyType']:checked")
            var bodyTypeArr = []
            var bodyTypeData =[]

            for(i = 0; i < bodyType.length;i++){
              bodyTypeArr.push(bodyType[i].value)
            }

            if(bodyTypeArr.length === 0){
              valid = false
              Swal.fire({
                text: "Please Select at least one.",
                confirmButtonColor: '#3CDD57',
                timer: 3000
              });
            }

            if(valid){
              for(i = 0; i < bodyType.length;i++){
                bodyTypeData.push(bodyTypeArr[i])
              }

              allData = {
                ...allData,
                bodyType  : bodyTypeData
            }

            }
          }


          //check if user selects main goal
          if(category === "mainGoal"){
            var mainGoal = document.querySelectorAll("input[name='mainGoal']:checked")
            var mainGoalArr = []
            var mainGoalData = []
            // console.log(mainGoal)

            for(i = 0; i < mainGoal.length;i++){
              mainGoalArr.push(mainGoal[i].value)
            }
            if(mainGoalArr.length === 0){
              valid = false
              Swal.fire({
                text: "Please Select at least one.",
                confirmButtonColor: '#3CDD57',
                timer: 3000
              });
            }

            if(valid){
              for(i = 0; i < mainGoal.length;i++){
                mainGoalData.push(mainGoalArr[i])
              }

              allData = {
                ...allData,
                mainGoal  : mainGoalData
            }
            }

          }

          //check if user select what his typical day looks like
          if(category === 'typicalDay'){
            var typicalDay = document.querySelectorAll("input[name='typicalDay']:checked")
            var typicalDayArr = []
            var typicalDayData = []

            for(i = 0; i < typicalDay.length;i++){
              typicalDayArr.push(typicalDay[i].value)
            }
            if(typicalDayArr.length === 0){
              valid = false
              Swal.fire({
                text: "Please Select at least one.",
                confirmButtonColor: '#3CDD57',
                timer: 3000
              });
            }

            if(valid){
              for(i = 0; i < typicalDay.length;i++){
                typicalDayData.push(typicalDayArr[i])
              }

              allData = {
                ...allData,
                typicalDay  : typicalDayData
            }
            }
          }

          //check if user select diet
          if(category === 'diet'){
            var diet = document.querySelectorAll("input[name='diet']:checked")
            console.log(diet)
            var dietArr = []
            var dietData = []

            for(i = 0; i < diet.length;i++){
                dietArr.push(diet[i].value)
            }
            if(dietArr.length === 0){
              valid = false
              Swal.fire({
                text: "Please Select at least one.",
                confirmButtonColor: '#3CDD57',
                timer: 3000
              });
            }

            if(valid){
              for(i = 0; i < diet.length;i++){
                dietData.push(dietArr[i])
              }
              allData = {
                ...allData,
                diet  : dietData
            }
            }
          }

          //check if user selects average night
          if(category === "sleep"){
            var sleep = document.querySelectorAll("input[name='sleep']:checked")
            var sleepArr = []
            var sleepData = []

            for(i = 0; i < sleep.length;i++){
                sleepArr.push(sleep[i].value)
            }
            if(sleepArr.length === 0){
              valid = false
              Swal.fire({
                text: "Please Select at least one.",
                confirmButtonColor: '#3CDD57',
                timer: 3000
              });
            }

            if(valid){
              for(i = 0; i < sleep.length;i++){
                sleepData.push(sleepArr[i])
              }
              allData = {
                ...allData,
                sleep  : sleepData
            }
            }
          }

          //check if user selects energy level
          if(category === "energyLevel"){
            var energyLevel = document.querySelectorAll("input[name='energyLevel']:checked")
            // console.log("energyLevel")
            var energyLevelArr = []
            var energyLevelData = []

            for(i = 0; i < energyLevel.length;i++){
                energyLevelArr.push(energyLevel[i].value)
            }
            if(energyLevelArr.length === 0){
              valid = false
              Swal.fire({
                text: "Please Select at least one.",
                confirmButtonColor: '#3CDD57',
                timer: 3000
              });
              //alert("Please Select at least one.")
            }

            if(valid){
              for(i = 0; i < energyLevel.length;i++){
                energyLevelData.push(energyLevelArr[i])
              }
              allData = {
                ...allData,
                energyLevel  : energyLevelData
            }
            }
          }

          //check if user selects ideal weight
          if(category === "idealWeight"){
            var idealWeight = document.querySelectorAll("input[name='idealWeight']:checked")
            // console.log("idealWeight")
            var idealWeightArr = []
            var idealWeightData = []

            for(i = 0; i < idealWeight.length;i++){
                idealWeightArr.push(idealWeight[i].value)
            }
            if(idealWeightArr.length === 0){
              valid = false
              Swal.fire({
                text: "Please Select at least one.",
                confirmButtonColor: '#3CDD57',
                timer: 3000
              });
              //alert("Please Select at least one.")
            }

            if(valid){
              for(i = 0; i < idealWeight.length;i++){
                idealWeightData.push(idealWeightArr[i])
              }
              allData = {
                ...allData,
                idealWeight  : idealWeightData
            }
            }
          }

          //check if user selects body area
          if(category === 'bodyArea'){
            var bodyArea = document.querySelectorAll("input[name='bodyArea']:checked")
            // console.log("bodyArea")
            var bodyAreaArr = []
            var bodyAreaData = []

            for(i = 0; i < bodyArea.length;i++){
                bodyAreaArr.push(bodyArea[i].value)
            }
            if(bodyAreaArr.length === 0){
              valid = false
              Swal.fire({
                text: "Please Select at least one.",
                confirmButtonColor: '#3CDD57',
                timer: 3000
              });
            }

            if(valid){
              for(i = 0; i < bodyArea.length;i++){
                bodyAreaData.push(bodyAreaArr[i])
              }
              allData = {
                ...allData,
                bodyArea  : bodyAreaData
            }
            }
          }

          //check if user selects physical activity
          if(category === 'physicalActivity'){
            var physicalActivity = document.querySelectorAll("input[name='physicalActivity']:checked")
            // console.log("physicalActivity")
            var physicalActivityArr = []
            var physicalActivityData = []

            for(i = 0; i < physicalActivity.length;i++){
                physicalActivityArr.push(physicalActivity[i].value)
            }
            if(physicalActivityArr.length === 0){
              valid = false
              Swal.fire({
                text: "Please Select at least one.",
                confirmButtonColor: '#3CDD57',
                timer: 3000
              });
            }

            if(valid){
              for(i = 0; i < physicalActivity.length;i++){
                physicalActivityData.push(physicalActivityArr[i])
              }
              allData = {
                ...allData,
                physicalActivity  : physicalActivityData
            }
            }
          }

          //check if user selects bad habits
          if(category === 'badHabits'){
            var badHabits = document.querySelectorAll("input[name='badHabits']:checked")
            // console.log("badHabits")
            var badHabitsArr = []
            var badHabitsData = []

            for(i = 0; i < badHabits.length;i++){
                badHabitsArr.push(badHabits[i].value)
            }
            if(badHabitsArr.length === 0){
              valid = false
              Swal.fire({
                text: "Please Select at least one.",
                confirmButtonColor: '#3CDD57',
                timer: 3000
              });
            }

            if(valid){
              for(i = 0; i < badHabits.length;i++){
                badHabitsData.push(badHabitsArr[i])
              }
              allData = {
                ...allData,
                badHabits  : badHabitsData
            }
            }
          }

          //check if user selects water intake
          if(category === 'waterIntake'){
            var waterIntake = document.querySelectorAll("input[name='waterIntake']:checked")
            // console.log("waterIntake")
            var waterIntakeArr = []
            var waterIntakeData = []

            for(i = 0; i < waterIntake.length;i++){
                waterIntakeArr.push(waterIntake[i].value)
            }
            if(waterIntakeArr.length === 0){
              valid = false
              Swal.fire({
                text: "Please Select at least one.",
                confirmButtonColor: '#3CDD57',
                timer: 3000
              });
            }

            if(valid){
              for(i = 0; i < waterIntake.length;i++){
                waterIntakeData.push(waterIntakeArr[i])
              }
              allData = {
                ...allData,
                waterIntake  : waterIntakeData
            }
            }
          }

          if(category === "weight"){
            var weight = document.querySelector('[name="weight"]')
            var idealWeight = document.querySelector('[name="idealWeightInput"]')

            if(valid){
                var overallInches = allData.bodyMeasurements.height
                const bmi = (parseInt(weight.value)/(overallInches*overallInches))*703
                const waist = allData.bodyMeasurements.waist
                const neck = allData.bodyMeasurements.neck
                const hip = allData.bodyMeasurements.hip
                const age = allData.bodyMeasurements.age
                var bfp
                var bmr

                if(allData.bodyMeasurements.gender === "male"){
                    console.log("male bfp")
                    bfp= Math.round((86.010*(Math.log(waist*1-neck*1)/Math.log(10))-70.041*(Math.log(overallInches)/Math.log(10))+36.76*1)*100)/100;
                    bmr = (parseInt(weight.value)*4.536) + (parseInt(overallInches)*15.88) + - (parseInt(age)*5) + 5
                }
                if(allData.bodyMeasurements.gender === "female"){
                    console.log("female bfp")
                    bfp = Math.round((163.205*(Math.log(waist*1.0+hip*1.0-neck*1.0)/Math.log(10))- 97.684*(Math.log(overallInches)/Math.log(10))-78.387*1.0)*100)/100;
                    bmr = (parseInt(weight.value)*4.536) + (parseInt(overallInches)*15.88) + - (parseInt(age)*5) - 161
                }

                weightData = {
                    weight: weight.value,
                    idealWeight: idealWeight.value,
                    bmi : bmi,
                    bfp : bfp,
                    bmr:bmr
                  }
                allData = {
                  ...allData,
                  weight  : weightData
              }
            }
          }

          //check if user selects member plan
          if(category === 'memberPlan'){
            var memberPlan = document.querySelectorAll("input[name='memberPlan']:checked")
            // console.log("memberPlan")
            var memberPlanArr = []
            var memberPlanData = []

            for(i = 0; i < memberPlan.length;i++){
                memberPlanArr.push(memberPlan[i].value)
            }
            if(memberPlanArr.length === 0){
              valid = false
              Swal.fire({
                text: "Please Select at least one.",
                confirmButtonColor: '#3CDD57',
                timer: 3000
              });
            }

            if(valid){
              for(i = 0; i < memberPlan.length;i++){
                memberPlanData.push(memberPlanArr[i])
              }
              allData = {
                ...allData,
                memberPlan  : memberPlanData
            }
            }
          }

          //check if user select profecienct
          if(category === 'proficiency'){
            var proficiency = document.querySelectorAll("input[name='proficiency']:checked")
            // console.log("proficiency")
            var proficiencyArr = []
            var proficiencyData = []

            for(i = 0; i < proficiency.length;i++){
                proficiencyArr.push(proficiency[i].value)
            }
            if(proficiencyArr.length === 0){
              valid = false
              Swal.fire({
                text: "Please Select at least one.",
                confirmButtonColor: '#3CDD57',
                timer: 3000
              });
            }

            if(valid){
              for(i = 0; i < proficiency.length;i++){
                proficiencyData.push(proficiencyArr[i])
              }
              allData = {
                ...allData,
                proficiency  : proficiencyData
            }
            }

            $.ajax({
                url : 'customerCreate',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:  {"allData":allData},

                success   : function(data) {

                    if(allData.memberPlan==1){
                        window.location.href = "/home";
                    }else{
                        window.location.href = "/customer_payment";
                    }

                    //location.reload();
                },
                // error : function(err){
                //     console.log(err)
                // }
            });

          }

          // If the valid status is true, mark the step as finished and valid:
          if (valid) {
            // document.getElementsByClassName("step")[currentTab].className += " finish";
            // allData = {
            //     personalInfo : personalInfoData,
            //     bodyMeasurements : bodyMeasurementsData,
            //     physicalLimitations : physicalLimitationsData,
            //     preferedActivities : preferedActivitiesData,
            //     bodyType : bodyTypeData,
            //     mainGoal : mainGoalData,
            //     typicalDay : typicalDayData,
            //     diet : dietData
            // }
            // console.log(personalInfoData,bodyMeasurementsData,physicalLimitationsData,preferedActivitiesData,bodyTypeData,mainGoalData,typicalDayData,dietData)
            console.log(allData)


          }
          return valid; // return the valid status
        }



        // function fixStepIndicator(n) {
        //   // This function removes the "active" class of all steps...
        //   var i, x = document.getElementsByClassName("step");
        //   for (i = 0; i < x.length; i++) {
        //     x[i].className = x[i].className.replace(" active", "");
        //   }
        //   //... and adds the "active" class to the current step:
        //   x[n].className += " active";
        // }
// })
