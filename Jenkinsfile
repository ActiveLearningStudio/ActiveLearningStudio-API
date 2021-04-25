node('currikionelxp') {
      def app     
      stage('Clone repository') {               
            checkout scm    
      }     
      stage('Build image') {         
            app = docker.build("quay.io/curriki/api")    
       }     
      stage('Test image') {           
            app.inside { 
                  sh 'echo "Tests passed"'        
            }    
        }     
       stage('Push image') {	
            docker.withRegistry('https://quay.io', 'docker-private-credentials') {            
                  app.push("${env.BUILD_NUMBER}")            
                  app.push("develop")        
            }    
      }
      stage('Deploying Image') {

        echo 'Copy'
        sh "yes | docker stack deploy --compose-file ~/curriki/docker-compose.yml currikistack" 
        echo 'Copy completed'
    } 
}
