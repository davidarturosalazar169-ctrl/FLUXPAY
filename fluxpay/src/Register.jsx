import { useNavigate } from "react-router-dom";
import "./register.css";

export default function Register() {
  const navigate = useNavigate();

  const handleRegister = (e) => {
    e.preventDefault();
    alert("Cuenta creada correctamente");
  };

  return (
    <div className="register-page">

      <div className="register-sidebar">
        <div className="register-logo-container">
          <img 
            src="/fluxpay.jpg" 
            alt="FluxPay Logo" 
            className="register-logo" 
          />
        </div>
      </div>

      <div className="register-content">
        <h1 className="register-title">REGISTRARSE</h1>

        <form className="register-card" onSubmit={handleRegister}>

          <div className="register-grid">

            <input type="email" placeholder="Correo" className="register-input" required />
            <input type="text" placeholder="Nombre" className="register-input" required />
            <input type="password" placeholder="Contraseña" className="register-input" required />
            <input type="text" placeholder="Banco" className="register-input" required />
            <input type="text" placeholder="Nombre negocio" className="register-input" />
            <input type="text" placeholder="Cuenta bancaria" className="register-input" />

          </div>

          <button type="submit" className="register-btn-primary">
            Crear Cuenta
          </button>

          <button 
            type="button"
            className="register-btn-secondary"
            onClick={() => navigate("/")}>
            Volver al Login
          </button>

        </form>
      </div>
    </div>
  );
}