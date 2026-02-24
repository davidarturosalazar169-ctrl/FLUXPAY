import "./styles.css";

export default function Register() {
  return (
    <div className="page">
      <div className="side-bar">
        <div className="logo">RunPay</div>
      </div>

      <div className="content">
        <h1 className="title">REGISTRARSE</h1>

        <div className="card-register">
          <div className="form-grid">
            <input type="text" placeholder="Correo" className="input" />
            <input type="text" placeholder="Nombre" className="input" />

            <input type="password" placeholder="Contraseña" className="input" />
            <input type="text" placeholder="Banco" className="input" />

            <input type="text" placeholder="Nombre negocio" className="input" />
            <input type="text" placeholder="Cuenta bancaria" className="input" />
          </div>

          <button className="btn-primary large">
            REGISTRARSE
          </button>
        </div>
      </div>
    </div>
  );
}