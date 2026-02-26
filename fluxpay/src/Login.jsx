import { useNavigate } from "react-router-dom";
import "./login.css";

export default function Login() {
  const navigate = useNavigate();

  const handleLogin = (e) => {
    e.preventDefault();
    alert("Inicio de sesión");
  };

  return (
    <div className="login-page">
      
      {/* SIDEBAR */}
      <div className="login-sidebar">
        <div className="login-logo-container">
          <img 
            src="/fluxpay.jpg" 
            alt="FluxPay Logo" 
            className="login-logo" 
          />
        </div>
      </div>

      {/* CONTENIDO */}
      <div className="login-content">
        
        <div className="login-wrapper">
          
          <h1 className="login-title">INICIAR SESIÓN</h1>

          <form className="login-card" onSubmit={handleLogin}>
            
            <input 
              type="email" 
              placeholder="Email" 
              className="login-input"
              required
            />

            <input 
              type="password" 
              placeholder="Password" 
              className="login-input"
              required
            />

            <button type="submit" className="login-btn-primary">
              Iniciar sesión
            </button>

            <button 
              type="button"
              className="login-btn-secondary"
              onClick={() => navigate("/register")}
            >
              Registrate
            </button>

          </form>

        </div> {/* login-wrapper */}

      </div> {/* login-content */}

    </div> /* login-page */
  );
}