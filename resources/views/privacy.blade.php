@extends('layouts.app')
@section('title', 'Políticas de Privacidad')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card glass-card border-0 shadow-sm animate-fade-in-up">
                <div class="card-body p-4 p-md-5">
                    
                    <div class="text-center mb-5">
                        <h1 class="fw-bold text-unipaz mb-3">Políticas de Privacidad y Tratamiento de Datos</h1>
                        <div class="divider-green mx-auto mb-4"></div>
                        <p class="text-muted">Última actualización: {{ date('d/m/Y') }}</p>
                    </div>

                    <div class="privacy-content" style="line-height: 1.8; color: #4b5563;">
                        <h4 class="fw-bold text-dark mt-4 mb-3">1. Introducción</h4>
                        <p>
                            El <strong>Instituto Universitario de la Paz (UNIPAZ)</strong>, a través de su plataforma <strong>Emplea-UNIPAZ</strong>, respeta su privacidad y está comprometido con la protección de sus datos personales. Esta política de privacidad le informará sobre cómo cuidamos sus datos cuando visita nuestro sitio web y le informará sobre sus derechos de privacidad y cómo la ley lo protege, en conformidad con la <strong>Ley 1581 de 2012</strong> (Ley Estatutaria de Protección de Datos Personales en Colombia).
                        </p>

                        <h4 class="fw-bold text-dark mt-4 mb-3">2. Los datos que recopilamos sobre usted</h4>
                        <p>Los datos personales, o información personal, significan cualquier información sobre un individuo a partir de la cual esa persona puede ser identificada. Podemos recopilar, usar, almacenar y transferir diferentes tipos de datos personales sobre usted, que hemos agrupado de la siguiente manera:</p>
                        <ul>
                            <li><strong>Datos de Identidad:</strong> Incluyen nombre, apellido, código estudiantil, documento de identidad.</li>
                            <li><strong>Datos de Contacto:</strong> Incluyen dirección de correo electrónico institucional, número de teléfono.</li>
                            <li><strong>Datos Profesionales y Académicos:</strong> Perfil académico, semestre en curso, programa académico, y la hoja de vida (CV) cargada a la plataforma.</li>
                            <li><strong>Datos de Empresa (para usuarios empresariales):</strong> Nombre de la empresa, NIT, información del representante, sector y datos de contacto corporativo.</li>
                        </ul>

                        <h4 class="fw-bold text-dark mt-4 mb-3">3. ¿Cómo usamos sus datos personales?</h4>
                        <p>Solo usaremos sus datos personales cuando la ley nos lo permita. Más comúnmente, usaremos sus datos en las siguientes circunstancias:</p>
                        <ul>
                            <li>Para registrarlo como nuevo estudiante o empresa en la plataforma.</li>
                            <li>Para facilitar el proceso de intermediación laboral (conectar el perfil del estudiante con las empresas que publican vacantes).</li>
                            <li>Cuando deba compartir su perfil (incluyendo hoja de vida) con las empresas a las que decide postularse voluntariamente.</li>
                            <li>Para gestionar nuestra relación con usted, lo que incluirá notificarle sobre cambios en nuestros términos o políticas.</li>
                        </ul>

                        <div class="alert alert-info mt-4 mb-4 border-0" style="background: var(--unipaz-blue-tint);">
                            <i class="bi bi-shield-lock-fill me-2 text-unipaz fs-5 align-middle"></i>
                            <strong>Nota importante:</strong> UNIPAZ no vende, alquila ni comercializa sus datos personales con terceros bajo ninguna circunstancia.
                        </div>

                        <h4 class="fw-bold text-dark mt-4 mb-3">4. Autenticación a través de Google</h4>
                        <p>
                            Para los estudiantes, nuestra plataforma utiliza la autenticación de Google (Google OAuth) estrictamente validada para el dominio <code>@unipaz.edu.co</code>. Solo obtenemos su nombre y correo electrónico básico proporcionado por Google para crear su sesión segura.
                        </p>

                        <h4 class="fw-bold text-dark mt-4 mb-3">5. Seguridad de los datos</h4>
                        <p>
                            Hemos implementado medidas de seguridad apropiadas para evitar que sus datos personales se pierdan accidentalmente, se usen o se acceda a ellos de manera no autorizada, se modifiquen o se divulguen. Además, limitamos el acceso a sus datos personales a aquellos empleados y empresas registradas y aprobadas en el sistema.
                        </p>

                        <h4 class="fw-bold text-dark mt-4 mb-3">6. Retención de datos</h4>
                        <p>
                            Solo conservaremos sus datos personales durante el tiempo que sea necesario para cumplir con los fines para los que los recopilamos, incluso para satisfacer cualquier requisito legal, contable o de informes. Usted puede solicitar la eliminación de su cuenta y sus datos en cualquier momento contactando con soporte técnico.
                        </p>

                        <h4 class="fw-bold text-dark mt-4 mb-3">7. Contacto</h4>
                        <p>
                            Si tiene alguna pregunta sobre esta política de privacidad o sobre nuestras prácticas de privacidad, comuníquese con nosotros a través de:
                        </p>
                        <ul>
                            <li><strong>Correo electrónico:</strong> contacto@unipaz.edu.co</li>
                            <li><strong>Sede:</strong> Barrancabermeja, Santander, Colombia.</li>
                        </ul>
                    </div>
                    
                    <div class="text-center mt-5">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary px-4">
                            <i class="bi bi-arrow-left me-2"></i>Volver al Inicio
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
