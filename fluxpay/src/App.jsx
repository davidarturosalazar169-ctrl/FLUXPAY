import { Routes, Route } from "react-router-dom";
import Login from "./Login";
import Register from "./Register";
import Dashboard from "./Cliente/Dashboard";
import ClienteTarjeta from "./Cliente/ClienteTarjeta";

function App() {
  return (
    <Routes>
      <Route path="/" element={<Login />} />
      <Route path="/register" element={<Register />} />
       <Route path="/dashboard" element={<Dashboard />} />

      <Route path="/Cliente/clienteTarjetas" element={<ClienteTarjeta />} />
    </Routes>
  );
}

export default App;