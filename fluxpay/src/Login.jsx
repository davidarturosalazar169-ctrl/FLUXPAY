import "./styles.css";


export default function Login() {
  return (
    <div className="page">
      <div className="side-bar">
        <div className="logo">RunPay</div>
      </div>

      <div className="content">
        <h1 className="title">INICIAR SESION</h1>

        <div className="card-login">
          <input type="text" placeholder="Email" className="input" />
          <input type="password" placeholder="Password" className="input" />

          <button className="btn-primary">Sign in</button>
          <button className="btn-secondary">Register</button>

          <div className="social">
            <button className="social-btn">G</button>
            <button className="social-btn">F</button>
            <button className="social-btn">X</button>
          </div>

          <button className="btn-guest">Invitado</button>
        </div>
      </div>
    </div>
  );
}