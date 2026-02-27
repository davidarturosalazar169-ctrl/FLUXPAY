import "../styles.css";
import Navbar from "../Navbar";
import "bootstrap/dist/css/bootstrap.min.css";

export default function DashboardCliente() {
  return (
    <div className="page">
      
      {/* Sidebar */}
      <div className="side-bar">
        <div className="logo-container">
          <img
            src="/fluxpay.jpg"
            alt="FluxPay Logo"
            className="logo-img"
          />
        </div>
      </div>
      
      {/* Contenido principal */}
      <div className="main-content">
        
        <Navbar
          nombre="Alexander Castillo"
          correo="Alexander.Correo@Gmail.com  "
          rol="Cliente"
        />

        <div className="container">
          <h1>Dashboard</h1>

          <div className="row mt-4">

            {/* Cuenta Bancaria */}
            <div className="col-12 col-md-6 mb-4">
              <div className="card h-100 border-0 shadow-sm rounded-4">
                <div className="card-body text-center py-5">

                  <div className="mb-3">
                    <svg width="70" height="70" viewBox="0 0 24 24" fill="none" stroke="#263238" strokeWidth="1.5">
                      <rect x="2" y="5" width="20" height="14" rx="3"></rect>
                      <line x1="2" y1="9" x2="22" y2="9"></line>
                    </svg>
                  </div>

                  <h5 className="fw-bold">Cuenta Bancaria</h5>

                </div>
              </div>
            </div>

            {/* Historial */}
            <div className="col-12 col-md-6 mb-4">
              <div className="card h-100 border-0 shadow-sm rounded-4">
                <div className="card-body text-center py-5">

                  <div className="mb-3">
                    <svg width="70" height="70" viewBox="0 0 24 24" fill="none" stroke="#263238" strokeWidth="1.5">
                      <line x1="12" y1="2" x2="12" y2="22"></line>
                      <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                  </div>

                  <h5 className="fw-bold">Historial de Pagos</h5>

                </div>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>
  );
}