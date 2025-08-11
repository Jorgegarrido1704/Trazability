
    let timerInterval;
    let isRunning = false;
    let startTime = 0;
    let elapsedTime = 0;
    let lapStartTime = 0;
    let laps = [];

    const startStopButton = document.getElementById('startStopButton');
    const lapButton = document.getElementById('lapButton');
    const submitButton = document.getElementById('submitButton');
    const lapsInput = document.getElementById('laps');
    const timerDisplay = document.getElementById('timerDisplay');
    const lapDisplay = document.getElementById('lapDisplay');
    const lapList = document.getElementById('lapList');
    const lapsres = document.getElementById('lapsres');

    startStopButton.addEventListener('click', () => {
        if (isRunning) {
            clearInterval(timerInterval);
            startStopButton.textContent = 'Start';
            lapButton.disabled = true;
            submitButton.disabled = false;
        } else {
            startTime = Date.now() - elapsedTime;
            lapStartTime = Date.now(); // Reset lap start time for each new session
            timerInterval = setInterval(updateTime, 100);
            startStopButton.textContent = 'Stop';
            lapButton.disabled = false;
        }
        isRunning = !isRunning;
    });

    lapButton.addEventListener('click', () => {
        const lapTime = formatTime(elapsedTime);
        laps.push(lapTime);
        lapsInput.value = laps.join('-');


        // Reset lap timer to 00:00:000 for next lap
        lapStartTime = Date.now();

        // Display lap time in the list

    });

    function updateTime() {
        elapsedTime = Date.now() - startTime;
        const formattedTime = formatTime(elapsedTime);
        timerDisplay.textContent = formattedTime; // Display total time on the page

        const lapElapsedTime = Date.now() - lapStartTime; // Time since last lap started
        const formattedLapTime = formatTime(lapElapsedTime);
        lapDisplay.textContent = `Lap Time: ${formattedLapTime}`; // Display lap time on the page
    }

    function formatTime(ms) {
        const minutes = Math.floor(ms / 60000);
        const seconds = Math.floor((ms % 60000) / 1000);
        const milliseconds = ms % 1000;
        //constant  = sin milesimas 

        let constant = parseInt((minutes * 60) + (seconds) + (milliseconds / 1000));
        return `${constant}`;
    }

    function padZero(num, length = 2) {
        return num.toString().padStart(length, '0');
    }

    const proceso = document.getElementById('proceso');
    const subproceso = document.getElementById('subproceso');
    const asset=document.getElementById('DescriptionProcess');

    function diffprocess() {


        var corte = ['', 'cutting w/2 terminals without saels',
            'cutting w/1 terminals without saels',
            'cutting w/1 terminals with saels',
            'cutting w/2 terminals with saels',
            'cutting without terminals',
            'cutting without terminals big size',

        ];
       
        var terminales = ['',
            'twist wire',
            'terminal apply with machine',
            'terminal apply with handtool',
            'create set for splice',
            'splice set apply with machine',
            'splice set apply with handtool',
            'put seal on wire',
            'set heatshrink',
            'burn heatshrirnk w/heatgun',
            'burn heatshrink w/machine',
            'set tin point',
            'terminal apply with cannon machine',
        ];

        var assembly = ['', 'subassembly',
            'assembly',
            'Add Ties',
            'Tapping Body/Assembly',
            'Tapping breakout/Assembly',
            'Plugging/Sub-Assembly',
            'Plugging/Assembly',
            

        ];
        var loom = ['', 'looming',
            'braiding',
            'set slavee for braiding',
            'Tapping/Looming',
            'labeling',

        ];

        if (proceso.value == 'Cutting') {
            subproceso.innerHTML = '';
            for (let i = 0; i < corte.length; i++) {
                subproceso.innerHTML += `<option value="${corte[i]}">${corte[i]}</option>`;
            }

        } else if (proceso.value == 'Terminals') {
            subproceso.innerHTML = '';
            for (let i = 0; i < terminales.length; i++) {
                subproceso.innerHTML += `<option value="${terminales[i]}">${terminales[i]}</option>`;
            }
        } else if (proceso.value == 'Assembly') {
            subproceso.innerHTML = '';
            for (let i = 0; i < assembly.length; i++) {
                subproceso.innerHTML += `<option value="${assembly[i]}">${assembly[i]}</option>`;
            }
        } else if (proceso.value == 'Looming') {
            subproceso.innerHTML = '';
            for (let i = 0; i < loom.length; i++) {
                subproceso.innerHTML += `<option value="${loom[i]}">${loom[i]}</option>`;
            }
        }
    }

    function suproceso() {

      //maquinas de corte 
       var maquinasCon2 = ['', 'FB-045','FB-036','FB-046'];
        var maquinasCon1 = ['', 'FB-045','FB-036','FB-046','FB-059','FB-048'];

        //maquinas de terminales
        $crimpers=['','FB-081','FB-082','FB-083','FB-084','FB-085','FB-086','FB-087','FB-088','FB-089','FB-090','FB-091','FB-092','FB-093'];
        $cannon=['','FB-089','873','874'];

        let processNumber = document.getElementById('Process_Number');
        let asset = document.getElementById('DescriptionProcess');
        if (subproceso.value == 'cutting w/2 terminals without saels' || subproceso.value == 'cutting w/1 terminals without saels' ||
            subproceso.value == 'cutting w/1 terminals with saels' || subproceso.value == 'cutting w/2 terminals with saels' ||
            subproceso.value == 'cutting without terminals' || subproceso.value == 'twist wire') {
            document.getElementById('size-mm').style.display = 'block';
        }else{
            document.getElementById('size-mm').style.display = 'none';
        }

        //BIG SIZE
        if(subproceso.value == 'cutting without terminals big size'){
           document.getElementById('Process_Number').value = '10011';
           document.getElementById('DescriptionProcess').value = 'FB022';
        }
         //with terminals and seals
           if(subproceso.value =='cutting w/1 terminals with saels'){
            document.getElementById('Process_Number').value = '10051';
            document.getElementById('DescriptionProcess').value = 'FB036';
         }if(subproceso.value =='cutting w/2 terminals with saels'){
            document.getElementById('Process_Number').value = '10052';
            document.getElementById('DescriptionProcess').value = 'FB036';
         }
        //without terminals neither seals
        if(subproceso.value =='cutting w/2 terminals without saels'){
           document.getElementById('Process_Number').value = '10041';
           asset.innerHTML = '';
           for($i=0;$i<maquinasCon2.length;$i++){
              asset.innerHTML += `<option value="${maquinasCon2[$i]}">${maquinasCon2[$i]}</option>`;
           }
           
        }if(subproceso.value =='cutting w/1 terminals without saels'){
            document.getElementById('Process_Number').value = '10021';
            asset.innerHTML = '';
           for($i=0;$i<maquinasCon1.length;$i++){
              asset.innerHTML += `<option value="${maquinasCon1[$i]}">${maquinasCon1[$i]}</option>`;
           }
         }if(subproceso.value =='cutting without terminals'){
            document.getElementById('Process_Number').value = '10001';
            asset.innerHTML = '';
            for($i=0;$i<maquinasCon1.length;$i++){
              asset.innerHTML += `<option value="${maquinasCon1[$i]}">${maquinasCon1[$i]}</option>`;
           }
         }
         //pending folio
         if(subproceso.value =='twist wire'){
            document.getElementById('Process_Number').value = '10061';
            document.getElementById('DescriptionProcess').value = 'Pend';
         }


         //terminales
         if(subproceso.value =='terminal apply with machine'){
            document.getElementById('Process_Number').value = '10081';
            asset.innerHTML = '';
            for($i=0;$i<$crimpers.length;$i++){
              asset.innerHTML += `<option value="${$crimpers[$i]}">${$crimpers[$i]}</option>`;
           }
         }if(subproceso.value =='terminal apply with handtool'){
            document.getElementById('Process_Number').value = '10101';
            document.getElementById('DescriptionProcess').value = 'Pend';
         }if(subproceso.value =='terminal apply with cannon machine'){
            document.getElementById('Process_Number').value = '10095';
            asset.innerHTML = '';
            for($i=0;$i<$cannon.length;$i++){
              asset.innerHTML += `<option value="${$cannon[$i]}">${$cannon[$i]}</option>`;
           }
         }
          

         //splices  mESAS 
         $mesasSpliceSet=['','FB-065','FB-066','FB-067','FB-068','FB-069'];
         
         if(subproceso.value =='create set for splice'){
            document.getElementById('Process_Number').value = '10341';
             asset.innerHTML = '';
            for($i=0;$i<mesasSpliceSet.length;$i++){
              asset.innerHTML += `<option value="${mesasSpliceSet[$i]}">${mesasSpliceSet[$i]}</option>`;
           }

           // SPLICE MACHINES
           $machineSplice=['','FB-110','FB-115','FB-077'];
         }if(subproceso.value =='splice set apply with machine'){
            document.getElementById('Process_Number').value = '10301';
            asset.innerHTML = '';
            for($i=0;$i<mesasSpliceSet.length;$i++){
              asset.innerHTML += `<option value="${mesasSpliceSet[$i]}">${mesasSpliceSet[$i]}</option>`;
           }
         }if(subproceso.value =='splice set apply with handtool'){
            document.getElementById('Process_Number').value = '10321';
            document.getElementById('DescriptionProcess').value = 'Pend';
         }if(subproceso.value =='put seal on wire'){
            document.getElementById('Process_Number').value = '10381';
             asset.innerHTML = '';
            for($i=0;$i<mesasSpliceSet.length;$i++){
              asset.innerHTML += `<option value="${mesasSpliceSet[$i]}">${mesasSpliceSet[$i]}</option>`;
           }
         }if(subproceso.value =='set heatshrink'){
            document.getElementById('Process_Number').value = '10361';
             asset.innerHTML = '';
            for($i=0;$i<mesasSpliceSet.length;$i++){
              asset.innerHTML += `<option value="${mesasSpliceSet[$i]}">${mesasSpliceSet[$i]}</option>`;
           }
         }
         //heat Gun
            $heatGun=['','1753','1754'];
         if(subproceso.value =='burn heatshrink w/heatgun'){
            document.getElementById('Process_Number').value = '10401';
            asset.innerHTML = '';
            for($i=0;$i<heatGun.length;$i++){
              asset.innerHTML += `<option value="${heatGun[$i]}">${heatGun[$i]}</option>`;
            }

            //MACHINES for heatshrink

            $machineHeatshrink=['','FB-079','FB-020','FB-080'];
         }if(subproceso.value =='burn heatshrink w/machine'){
            document.getElementById('Process_Number').value = '10421';
            document.getElementById('DescriptionProcess').value = 'FB-078';

         }if(subproceso.value =='set tin point'){
            document.getElementById('Process_Number').value = '10431';
            document.getElementById('DescriptionProcess').value = 'Pend';
         }
            //Mesas subasembly
            
            $mesasAss=['','FB-004','FB-007','FB-023','SWT-1','SWT-2','SWT-3','RF-110',"AST-1","AST-2","AST-3","AST-4","AST-5","AST-6","AST-7","AST-8","AST-9","AST-10","AST-11","AST-12","AST-13","AST-14","AST-15","AST-16","AST-17","AST-18","AST-19","AST-20"];
            $boards=['',"BL1-1","BL1-2","BL1-3","BL1-4","BL1-5","BL1-6","BL1-7","BL1-8","BL1-9","BL1-10","BL1-11","BL1-12","BL1-13","BL1-14","BL1-15","BL1-16","BL1-17","BL1-18"
              ,"BL2-1","BL2-2","BL2-3","BL2-4","BL2-5","BL2-6","BL2-7","BL2-8","BL2-9","BL2-10","BL2-11","BL2-12","BL2-13","BL2-14","BL2-15","BL2-16","BL2-17","BL2-18"
               ,"BL3-1","BL3-2","BL3-3","BL3-4","BL3-5","BL3-6","BL3-7","BL3-8","BL3-9","BL3-10","BL3-11","BL3-12","BL3-13","BL3-14","BL3-15","BL3-16","BL3-17","BL3-18"
,"BL4-1","BL4-2","BL4-3","BL4-4","BL4-5","BL4-6","BL4-7","BL4-8","BL4-9","BL4-10","BL4-11","BL4-12","BL4-13","BL4-14","BL4-15","BL4-16","BL4-17","BL4-18"
,"BL5-1","BL5-2","BL5-3","BL5-4","BL5-5","BL5-6","BL5-7","BL5-8","BL5-9","BL5-10","BL5-11","BL5-12","BL5-13","BL5-14","BL5-15","BL5-16","BL5-17","BL5-18"
,"BL6-1","BL6-2","BL6-3","BL6-4","BL6-5","BL6-6","BL6-7","BL6-8","BL6-9","BL6-10","BL6-11","BL6-12","BL6-13","BL6-14","BL6-15","BL6-16","BL6-17","BL6-18"] ;   
         if(subproceso.value =='subassembly'){
            document.getElementById('Process_Number').value = '10441';
             asset.innerHTML = '';
            for($i=0;$i<$mesasAss.length;$i++){
              asset.innerHTML += `<option value="${$mesasAss[$i]}">${$mesasAss[$i]}</option>`;
            }
         }if(subproceso.value =='assembly'){
            document.getElementById('Process_Number').value = '10501';
             asset.innerHTML = '';
            for($i=0;$i<$boards.length;$i++){
              asset.innerHTML += `<option value="${$boards[$i]}">${$boards[$i]}</option>`;
            }

            //lomming 
            $tablesLoom=['','LT-1','LT-2','LT-3','LT-4','LT-5','LT-6','LT-7','LT-8'];
         
         }if(subproceso.value =='looming'){
            document.getElementById('Process_Number').value = '10601';
              asset.innerHTML = '';
            for($i=0;$i<$tablesLoom.length;$i++){
              asset.innerHTML += `<option value="${$tablesLoom[$i]}">${$tablesLoom[$i]}</option>`;
            }
         }if(subproceso.value =='labeling'){
            document.getElementById('Process_Number').value = '10701';
            document.getElementById('DescriptionProcess').value = 'Pend';
        }if(subproceso.value =='Add Ties'){
            document.getElementById('Process_Number').value = '10801';
            document.getElementById('DescriptionProcess').value = 'Pend';
         }if(subproceso.value =='Tapping Body/Assembly'){
            document.getElementById('Process_Number').value = '10901';
            document.getElementById('DescriptionProcess').value = 'Pend';
         }
         if(subproceso.value =='Tapping breakout/Assembly'){
            document.getElementById('Process_Number').value = '10902';
            document.getElementById('DescriptionProcess').value = 'Pend';
         }if(subproceso.value =='Plugging/Sub-Assembly'){
            document.getElementById('Process_Number').value = '10951';
            document.getElementById('DescriptionProcess').value = 'Pend';
         }if(subproceso.value =='Plugging/Assembly'){
            document.getElementById('Process_Number').value = '10975';
            document.getElementById('DescriptionProcess').value = 'Pend';
         }if(subproceso.value =='Tapping/Looming'){
            document.getElementById('Process_Number').value = '11001';
            document.getElementById('DescriptionProcess').value = 'Pend';
         }
            //machine braiding
            $brading = ['','RF-023','RF-022','RF-021'];
         
         if(subproceso.value == 'braiding'){
            document.getElementById('Process_Number').value = '11101';
            document.getElementById('DescriptionProcess').value = 'Pend';
         }if(subproceso.value == 'set slavee for braiding'){
            document.getElementById('Process_Number').value = '11201';
            document.getElementById('DescriptionProcess').value = 'Pend';
         }


        }