<?php
$lang = "";
switch ($setlang) {
    case 'pt':
        $lang = "pt";
        break;
    case 'eng':
        $lang = "eng";
        break;
    default:
        $lang = "pt";
        break;
}
?>
<div class="pagina">
    <div class="grid-pagina-termos-politica">
        <div class="d-flex flex-row align-items-center font-pop justify-content-center">
            <button class="setCookieBtn btn pe-1 primary nav-link <?php if (!isset($_COOKIE['lang']) || $_COOKIE['lang'] == "pt") {
                                                                        echo "active";
                                                                    } ?>" name="pt">
                <span class="span-lang">pt</span>
                <div class="bordinhaazulhorizontal position-relative">
                    <img src="<?php echo get_link("") ?>libs/img/Rectangle_18.png" alt="Rectangle_18.png">
                </div>
            </button>
            <button class="setCookieBtn btn ps-1 primary nav-link <?php if (isset($_COOKIE['lang']) && $_COOKIE['lang'] == "eng") {
                                                                        echo "active";
                                                                    } ?>" name="eng">
                <span class="span-lang">eng</span>
                <div class="bordinhaazulhorizontal position-relative">
                    <img src="<?php echo get_link("") ?>libs/img/Rectangle_18.png" alt="Rectangle_18.png">
                </div>
            </button>
        </div>
        <div class="grid-termos-politica ">
            <?php
            if ($lang == 'pt') {
            ?>
                <div class="srcoll-div col-item s-back-1 p-4">
                    <h2><span style="color: rgb(68, 68, 68);">Política Privacidade</span></h2>
                    <p><span style="color: rgb(68, 68, 68);">A sua privacidade é importante para nós. É política do aminhabiblioteca respeitar a sua privacidade em relação a qualquer informação sua que possamos coletar no site <a href="localhost/aminhabiblioteca">aminhabiblioteca</a>, e outros sites que possuímos e operamos.</span></p>
                    <p><span style="color: rgb(68, 68, 68);">Solicitamos informações pessoais apenas quando realmente precisamos delas para lhe fornecer um serviço. Fazemo-lo por meios justos e legais, com o seu conhecimento e consentimento. Também informamos por que estamos coletando e como será usado.</span></p>
                    <p><span style="color: rgb(68, 68, 68);">Apenas retemos as informações coletadas pelo tempo necessário para fornecer o serviço solicitado. Quando armazenamos dados, protegemos dentro de meios comercialmente aceitáveis ​​para evitar perdas e roubos, bem como acesso, divulgação, cópia, uso ou modificação não autorizados.</span></p>
                    <p><span style="color: rgb(68, 68, 68);">Não compartilhamos informações de identificação pessoal publicamente ou com terceiros, exceto quando exigido por lei.</span></p>
                    <p><span style="color: rgb(68, 68, 68);">O nosso site pode ter links para sites externos que não são operados por nós. Esteja ciente de que não temos controle sobre o conteúdo e práticas desses sites e não podemos aceitar responsabilidade por suas respectivas&nbsp;</span><a href="https://politicaprivacidade.com/" rel="noopener noreferrer" target="_blank" style="background-color: transparent; color: rgb(68, 68, 68);">políticas de privacidade</a><span style="color: rgb(68, 68, 68);">.</span></p>
                    <p><span style="color: rgb(68, 68, 68);">Você é livre para recusar a nossa solicitação de informações pessoais, entendendo que talvez não possamos fornecer alguns dos serviços desejados.</span></p>
                    <p><span style="color: rgb(68, 68, 68);">O uso continuado de nosso site será considerado como aceitação de nossas práticas em torno de privacidade e informações pessoais. Se você tiver alguma dúvida sobre como lidamos com dados do usuário e informações pessoais, entre em contacto connosco.</span></p>
                    <p><span style="color: rgb(68, 68, 68);">
                            <ul>
                                <li><span style="color: rgb(68, 68, 68);">O serviço Google AdSense que usamos para veicular publicidade usa um cookie DoubleClick para veicular anúncios mais relevantes em toda a Web e limitar o número de vezes que um determinado anúncio é exibido para você.</span></li>
                                <li><span style="color: rgb(68, 68, 68);">Para mais informações sobre o Google AdSense, consulte as FAQs oficiais sobre privacidade do Google AdSense.</span></li>
                                <li><span style="color: rgb(68, 68, 68);">Utilizamos anúncios para compensar os custos de funcionamento deste site e fornecer financiamento para futuros desenvolvimentos. Os cookies de publicidade comportamental usados ​​por este site foram projetados para garantir que você forneça os anúncios mais relevantes sempre que possível, rastreando anonimamente seus interesses e apresentando coisas semelhantes que possam ser do seu interesse.</span></li>
                                <li><span style="color: rgb(68, 68, 68);">Vários parceiros anunciam em nosso nome e os cookies de rastreamento de afiliados simplesmente nos permitem ver se nossos clientes acessaram o site através de um dos sites de nossos parceiros, para que possamos creditá-los adequadamente e, quando aplicável, permitir que nossos parceiros afiliados ofereçam qualquer promoção que pode fornecê-lo para fazer uma compra.</span></li>
                            </ul>
                            <p><br></p>
                        </span></p>
                    <h3><span style="color: rgb(68, 68, 68);">Compromisso do Usuário</span></h3>
                    <p><span style="color: rgb(68, 68, 68);">O usuário se compromete a fazer uso adequado dos conteúdos e da informação que o aminhabiblioteca oferece no site e com caráter enunciativo, mas não limitativo:</span></p>
                    <ul>
                        <li><span style="color: rgb(68, 68, 68);">A) Não se envolver em atividades que sejam ilegais ou contrárias à boa fé a à ordem pública;</span></li>
                        <li><span style="color: rgb(68, 68, 68);">B) Não difundir propaganda ou conteúdo de natureza racista, xenofóbica, </span><span style="color: rgb(33, 37, 41);"><a href='https://bbebbet.br.com' style='color:inherit !important; text-decoration: none !important; font-size: inherit !important;'>bbebbet</a></span><span style="color: rgb(68, 68, 68);"> ou azar, qualquer tipo de pornografia ilegal, de apologia ao terrorismo ou contra os direitos humanos;</span></li>
                        <li><span style="color: rgb(68, 68, 68);">C) Não causar danos aos sistemas físicos (hardwares) e lógicos (softwares) do aminhabiblioteca, de seus fornecedores ou terceiros, para introduzir ou disseminar vírus informáticos ou quaisquer outros sistemas de hardware ou software que sejam capazes de causar danos anteriormente mencionados.</span></li>
                    </ul>
                    <h3><span style="color: rgb(68, 68, 68);">Mais informações</span></h3>
                    <p><span style="color: rgb(68, 68, 68);">Esperemos que esteja esclarecido e, como mencionado anteriormente, se houver algo que você não tem certeza se precisa ou não, geralmente é mais seguro deixar os cookies ativados, caso interaja com um dos recursos que você usa em nosso site.</span></p>
                    <p><span style="color: rgb(68, 68, 68);">Esta política é efetiva a partir de&nbsp;30 August 2024 15:49</span></p>
                </div>
                <div class="srcoll-div col-item s-back-2 p-4">
                    <h2><span style="color: rgb(68, 68, 68);">1. Termos</span></h2>
                    <p><span style="color: rgb(68, 68, 68);">Ao acessar ao site <a href="localhost/aminhabiblioteca">aminhabiblioteca</a>, concorda em cumprir estes termos de serviço, todas as leis e regulamentos aplicáveis ​​e concorda que é responsável pelo cumprimento de todas as leis locais aplicáveis. Se você não concordar com algum desses termos, está proibido de usar ou acessar este site. Os materiais contidos neste site são protegidos pelas leis de direitos autorais e marcas comerciais aplicáveis.</span></p>
                    <h2><span style="color: rgb(68, 68, 68);">2. Uso de Licença</span></h2>
                    <p><span style="color: rgb(68, 68, 68);">É concedida permissão para baixar temporariamente uma cópia dos materiais (informações ou software) no site aminhabiblioteca , apenas para visualização transitória pessoal e não comercial. Esta é a concessão de uma licença, não uma transferência de título e, sob esta licença, você não pode:&nbsp;</span></p>
                    <ol>
                        <li><span style="color: rgb(68, 68, 68);">modificar ou copiar os materiais;&nbsp;</span></li>
                        <li><span style="color: rgb(68, 68, 68);">usar os materiais para qualquer finalidade comercial ou para exibição pública (comercial ou não comercial);&nbsp;</span></li>
                        <li><span style="color: rgb(68, 68, 68);">tentar descompilar ou fazer engenharia reversa de qualquer software contido no site aminhabiblioteca;&nbsp;</span></li>
                        <li><span style="color: rgb(68, 68, 68);">remover quaisquer direitos autorais ou outras notações de propriedade dos materiais; ou&nbsp;</span></li>
                        <li><span style="color: rgb(68, 68, 68);">transferir os materiais para outra pessoa ou 'espelhe' os materiais em qualquer outro servidor.</span></li>
                    </ol>
                    <p><span style="color: rgb(68, 68, 68);">Esta licença será automaticamente rescindida se você violar alguma dessas restrições e poderá ser rescindida por aminhabiblioteca a qualquer momento. Ao encerrar a visualização desses materiais ou após o término desta licença, você deve apagar todos os materiais baixados em sua posse, seja em formato eletrónico ou impresso.</span></p>
                    <h2><span style="color: rgb(68, 68, 68);">3. Isenção de responsabilidade</span></h2>
                    <ol>
                        <li><span style="color: rgb(68, 68, 68);">Os materiais no site da aminhabiblioteca são fornecidos 'como estão'. aminhabiblioteca não oferece garantias, expressas ou implícitas, e, por este meio, isenta e nega todas as outras garantias, incluindo, sem limitação, garantias implícitas ou condições de comercialização, adequação a um fim específico ou não violação de propriedade intelectual ou outra violação de direitos.</span></li>
                        <li><span style="color: rgb(68, 68, 68);">Além disso, o aminhabiblioteca não garante ou faz qualquer representação relativa à precisão, aos resultados prováveis ​​ou à confiabilidade do uso dos materiais em seu site ou de outra forma relacionado a esses materiais ou em sites vinculados a este site.</span></li>
                    </ol>
                    <h2><span style="color: rgb(68, 68, 68);">4. Limitações</span></h2>
                    <p><span style="color: rgb(68, 68, 68);">Em nenhum caso o aminhabiblioteca ou seus fornecedores serão responsáveis ​​por quaisquer danos (incluindo, sem limitação, danos por perda de dados ou lucro ou devido a interrupção dos negócios) decorrentes do uso ou da incapacidade de usar os materiais em aminhabiblioteca, mesmo que aminhabiblioteca ou um representante autorizado da aminhabiblioteca tenha sido notificado oralmente ou por escrito da possibilidade de tais danos. Como algumas jurisdições não permitem limitações em garantias implícitas, ou limitações de responsabilidade por danos conseqüentes ou incidentais, essas limitações podem não se aplicar a você.</span></p>
                    <h2><span style="color: rgb(68, 68, 68);">5. Precisão dos materiais</span></h2>
                    <p><span style="color: rgb(68, 68, 68);">Os materiais exibidos no site da aminhabiblioteca podem incluir erros técnicos, tipográficos ou fotográficos. aminhabiblioteca não garante que qualquer material em seu site seja preciso, completo ou atual. aminhabiblioteca pode fazer alterações nos materiais contidos em seu site a qualquer momento, sem aviso prévio. No entanto, aminhabiblioteca não se compromete a atualizar os materiais.</span></p>
                    <h2><span style="color: rgb(68, 68, 68);">6. Links</span></h2>
                    <p><span style="color: rgb(68, 68, 68);">O aminhabiblioteca não analisou todos os sites vinculados ao seu site e não é responsável pelo conteúdo de nenhum site vinculado. A inclusão de qualquer link não implica endosso por aminhabiblioteca do site. O uso de qualquer site vinculado é por conta e risco do usuário.</span></p>
                    <p><br></p>
                    <h3><span style="color: rgb(68, 68, 68);">Modificações</span></h3>
                    <p><span style="color: rgb(68, 68, 68);">O aminhabiblioteca pode revisar estes termos de serviço do site a qualquer momento, sem aviso prévio. Ao usar este site, você concorda em ficar vinculado à versão atual desses termos de serviço.</span></p>
                    <h3><span style="color: rgb(68, 68, 68);">Lei aplicável</span></h3>
                    <p><span style="color: rgb(68, 68, 68);">Estes termos e condições são regidos e interpretados de acordo com as leis do aminhabiblioteca e você se submete irrevogavelmente à jurisdição exclusiva dos tribunais naquele estado ou localidade.</span></p>
                </div>
            <?php
            }
            if ($lang == 'eng') {
            ?>
                <div class="srcoll-div col-item s-back-1 p-4">
                    <h2><span style="color: rgb(68, 68, 68);">Privacy Policy</span></h2>
                    <p><span style="color: rgb(68, 68, 68);">Your privacy is important to us. It is aminhabiblioteca's policy to respect your privacy regarding any information we may collect from you across our website, <a href="localhost/aminhabiblioteca">aminhabiblioteca</a>, and other sites we own and operate.</span></p>
                    <p><span style="color: rgb(68, 68, 68);">We only ask for personal information when we truly need it to provide a service to you. We collect it by fair and lawful means, with your knowledge and consent. We also let you know why we’re collecting it and how it will be used.</span></p>
                    <p><span style="color: rgb(68, 68, 68);">We only retain collected information for as long as necessary to provide you with your requested service. What data we store, we’ll protect within commercially acceptable means to prevent loss and theft, as well as unauthorised access, disclosure, copying, use or modification.</span></p>
                    <p><span style="color: rgb(68, 68, 68);">We don’t share any personally identifying information publicly or with third-parties, except when required to by law.</span></p>
                    <p><span style="color: rgb(68, 68, 68);">Our website may link to external sites that are not operated by us. Please be aware that we have no control over the content and practices of these sites, and cannot accept responsibility or liability for their respective&nbsp;</span><a href="https://privacypolicies.in/" rel="noopener noreferrer" target="_blank" style="color: rgb(68, 68, 68);">privacy policies</a><span style="color: rgb(68, 68, 68);">.</span></p>
                    <p><span style="color: rgb(68, 68, 68);">You are free to refuse our request for your personal information, with the understanding that we may be unable to provide you with some of your desired services.</span></p>
                    <p><span style="color: rgb(68, 68, 68);">Your continued use of our website will be regarded as acceptance of our practices around privacy and personal information. If you have any questions about how we handle user data and personal information, feel free to contact us.</span></p>
                    <p><span style="color: rgb(68, 68, 68);">
                            <h2><span style="color: rgb(68, 68, 68);">Cookie Policy for aminhabiblioteca</span></h2>
                            <p><span style="color: rgb(68, 68, 68);"> </span></p>
                            <p><span style="color: rgb(68, 68, 68);">This is the Cookie Policy for aminhabiblioteca, accessible from URL localhost/aminhabiblioteca.</span></p>
                            <p><span style="color: rgb(68, 68, 68);"> </span></p>
                            <h3><span style="color: rgb(68, 68, 68);">What Are Cookies</span></h3>
                            <p><span style="color: rgb(68, 68, 68);"> </span></p>
                            <p><span style="color: rgb(68, 68, 68);">As is common practice with almost all professional websites this site uses cookies, which are tiny files that are downloaded to your computer, to improve your experience. This page describes what information they gather, how we use it and why we sometimes need to store these cookies. We will also share how you can prevent these cookies from being stored however this may downgrade or break certain elements of the sites functionality.</span></p>
                            <p><span style="color: rgb(68, 68, 68);"> </span></p>
                            <h3><span style="color: rgb(68, 68, 68);">How We Use Cookies</span></h3>
                            <p><span style="color: rgb(68, 68, 68);"> </span></p>
                            <p><span style="color: rgb(68, 68, 68);">We use cookies for a variety of reasons detailed below. Unfortunately in most cases there are no industry standard options for disabling cookies without completely disabling the functionality and features they add to this site. It is recommended that you leave on all cookies if you are not sure whether you need them or not in case they are used to provide a service that you use.</span></p>
                            <p><span style="color: rgb(68, 68, 68);"> </span></p>
                            <h3><span style="color: rgb(68, 68, 68);">Disabling Cookies</span></h3>
                            <p><span style="color: rgb(68, 68, 68);"> </span></p>
                            <p><span style="color: rgb(68, 68, 68);">You can prevent the setting of cookies by adjusting the settings on your browser (see your browser Help for how to do this). Be aware that disabling cookies will affect the functionality of this and many other websites that you visit. Disabling cookies will usually result in also disabling certain functionality and features of this site. Therefore it is recommended that you do not disable cookies.</span></p>
                            <p><span style="color: rgb(68, 68, 68);"> </span></p>
                            <h3><span style="color: rgb(68, 68, 68);">The Cookies We Set</span></h3>
                            <p><span style="color: rgb(68, 68, 68);"> </span></p>
                            <p><br></p>
                            <p><span style="color: rgb(68, 68, 68);"> </span></p>
                            <h3><span style="color: rgb(68, 68, 68);">Third Party Cookies</span></h3>
                            <p><span style="color: rgb(68, 68, 68);"> </span></p>
                            <p><span style="color: rgb(68, 68, 68);">In some special cases we also use cookies provided by trusted third parties. The following section details which third party cookies you might encounter through this site.</span></p>
                            <p><span style="color: rgb(68, 68, 68);"> </span></p>
                            <p><br></p>
                            <p><span style="color: rgb(68, 68, 68);"> </span></p>
                            <p><span style="color: rgb(68, 68, 68);"></span></p>
                            <p><span style="color: rgb(68, 68, 68);"> </span></p>
                            <p><br></p>
                        </span></p>
                    <h3><span style="color: rgb(68, 68, 68);">User's responsibilities</span></h3>
                    <p><span style="color: rgb(68, 68, 68);">The user undertakes the responsibility to make appropriate use of the contents and information offered on the site with enunciative, but not imitative, behaviour:</span></p>
                    <ul>
                        <li><span style="color: rgb(68, 68, 68);">A) Not to engage in activities that are illegal or contrary to good faith and public order;</span></li>
                        <li><span style="color: rgb(68, 68, 68);">B) Not to spread propaganda or content of a racist, xenophobic or gambling nature, any type of illegal pornography, terrorist claims or against human rights;</span></li>
                        <li><span style="color: rgb(68, 68, 68);">C) Do not cause damage to physical systems (hardware) and unattainable (software) of aminhabiblioteca, its suppliers or third parties, to introduce or disseminate computer viruses or any other hardware or software systems that are capable of causing damage previously mentioned.</span></li>
                    </ul>
                    <h3><span style="color: rgb(68, 68, 68);">More information</span></h3>
                    <p><span style="color: rgb(68, 68, 68);">Hopefully that has clarified things for you and as was previously mentioned if there is something that you aren't sure whether you need or not it's usually safer to leave cookies enabled in case it does interact with one of the features you use on our site.</span></p>
                    <p><span style="color: rgb(68, 68, 68);">This policy is effective as of 30 August 2024 16:31.</span></p>
                </div>
                <div class="srcoll-div col-item s-back-2 p-4">
                    <h2><span style="color: rgb(68, 68, 68);">1. Terms of Service</span></h2>
                    <h3><span style="color: rgb(68, 68, 68);">1. Terms</span></h3>
                    <p><span style="color: rgb(68, 68, 68);">By accessing the website at <a href="localhost/aminhabiblioteca">aminhabiblioteca</a> you are agreeing to be bound by these terms of service, all applicable laws and regulations, and agree that you are responsible for compliance with any applicable local laws. If you do not agree with any of these terms, you are prohibited from using or accessing this site. The materials contained in this website are protected by applicable copyright and trademark law.</span></p>
                    <h3><span style="color: rgb(68, 68, 68);">2. Use License</span></h3>
                    <ol>
                        <li><span style="color: rgb(68, 68, 68);">Permission is granted to temporarily download one copy of the materials (information or software) on aminhabiblioteca's website for personal, non-commercial transitory viewing only. This is the grant of a licence, not a transfer of title, and under this licence you may not:</span></li>
                        <li class="ql-indent-1"><span style="color: rgb(68, 68, 68);">modify or copy the materials;</span></li>
                        <li class="ql-indent-1"><span style="color: rgb(68, 68, 68);">use the materials for any commercial purpose, or for any public display (commercial or non-commercial);</span></li>
                        <li class="ql-indent-1"><span style="color: rgb(68, 68, 68);">attempt to decompile or reverse engineer any software contained on aminhabiblioteca website;</span></li>
                        <li class="ql-indent-1"><span style="color: rgb(68, 68, 68);">remove any copyright or other proprietary notations from the materials; or</span></li>
                        <li class="ql-indent-1"><span style="color: rgb(68, 68, 68);">transfer the materials to another person or 'mirror' the materials on any other server.</span></li>
                        <li><span style="color: rgb(68, 68, 68);">This licence shall automatically terminate if you violate any of these restrictions and may be terminated by aminhabiblioteca at any time. Upon terminating your viewing of these materials or upon the termination of this licence, you must destroy any downloaded materials in your possession whether in electronic or printed format.</span></li>
                    </ol>
                    <h3><span style="color: rgb(68, 68, 68);">3. Disclaimer</span></h3>
                    <ol>
                        <li><span style="color: rgb(68, 68, 68);">The materials on aminhabiblioteca's website are provided on an 'as is' basis. aminhabiblioteca makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties including, without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights.</span></li>
                        <li><span style="color: rgb(68, 68, 68);">Further, aminhabiblioteca does not warrant or make any representations concerning the accuracy, likely results, or reliability of the use of the materials on its website or otherwise relating to such materials or on any sites linked to this site.</span></li>
                    </ol>
                    <h3><span style="color: rgb(68, 68, 68);">4. Limitations</span></h3>
                    <p><span style="color: rgb(68, 68, 68);">In no event shall aminhabiblioteca or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials on aminhabiblioteca's website, even if aminhabiblioteca or a aminhabiblioteca authorised representative has been notified orally or in writing of the possibility of such damage. Because some jurisdictions do not allow limitations on implied warranties, or limitations of liability for consequential or incidental damages, these limitations may not apply to you.</span></p>
                    <h3><span style="color: rgb(68, 68, 68);">5. Accuracy of materials</span></h3>
                    <p><span style="color: rgb(68, 68, 68);">The materials appearing on aminhabiblioteca's website could include technical, typographical, or photographic errors. aminhabiblioteca does not warrant that any of the materials on its website are accurate, complete or current. aminhabiblioteca may make changes to the materials contained on its website at any time without notice. However aminhabiblioteca does not make any commitment to update the materials.</span></p>
                    <h3><span style="color: rgb(68, 68, 68);">6. Links</span></h3>
                    <p><span style="color: rgb(68, 68, 68);">aminhabiblioteca has not reviewed all of the sites linked to its website and is not responsible for the contents of any such linked site. The inclusion of any link does not imply endorsement by aminhabiblioteca of the site. Use of any such linked website is at the user's own risk.</span></p>
                    <h3><span style="color: rgb(68, 68, 68);">7. Modifications</span></h3>
                    <p><span style="color: rgb(68, 68, 68);">aminhabiblioteca may revise these terms of service for its website at any time without notice. By using this website you are agreeing to be bound by the then current version of these terms of service.</span></p>
                    <h3><span style="color: rgb(68, 68, 68);">8. Governing Law</span></h3>
                    <p><span style="color: rgb(68, 68, 68);">These terms and conditions are governed by and construed in accordance with the laws of aminhabiblioteca and you irrevocably submit to the exclusive jurisdiction of the courts in that State or location.</span></p>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>