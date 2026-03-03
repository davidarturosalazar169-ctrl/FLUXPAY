import React, { useState } from "react";
import { FaSearch } from "react-icons/fa";

export default function HistorialNegocio() {
  const colores = {
    primario: "#0e2a5a",    // Azul oscuro FluxPay
    azulBoton: "#11264c",   // Azul profundo de los botones de estado
    bordeContenedor: "#3b82f6", // El azul brillante del borde exterior
    textoGris: "#64748b",
  };

  const [transacciones] = useState([
    { id: 1, fecha: "2/12/2026", concepto: "Comida", monto: "$1912", hora: "7:00", estado: "Pendiente" },
    { id: 2, fecha: "2/12/2026", concepto: "Pago", monto: "$1121", hora: "12:00", estado: "En curso" },
    { id: 3, fecha: "5/12/2025", concepto: "Tienda", monto: "$871", hora: "9:00", estado: "Pendiente" },
    { id: 4, fecha: "2/01/2026", concepto: "Disp", monto: "$119", hora: "8:00", estado: "Pendiente" },
  ]);

  return (
    <div style={{ padding: "30px", backgroundColor: "#f4f7fa", minHeight: "100vh" }}>
      <h2 style={{ color: colores.primario, fontWeight: "bold", fontSize: "28px", marginBottom: "25px" }}>
        Historial
      </h2>

      {/* Contenedor con el borde azul de la imagen de referencia */}
      <div style={{ 
        background: "white", 
        borderRadius: "24px", 
        padding: "40px", 
    
        boxShadow: "0 4px 25px rgba(0,0,0,0.05)"
      }}>
        
        {/* Buscador estilo FluxPay */}
        <div style={{ 
          display: "flex", 
          alignItems: "center", 
          gap: "10px", 
          background: "white", 
          padding: "10px 15px", 
          borderRadius: "10px", 
          border: "1px solid #e2e8f0",
          width: "300px",
          marginBottom: "30px"
        }}>
          <FaSearch style={{ color: "#94a3b8" }} />
          <input 
            placeholder="Buscar transacción" 
            style={{ border: "none", outline: "none", width: "100%", color: colores.primario }} 
          />
        </div>

        <table style={{ width: "100%", borderCollapse: "collapse" }}>
          <thead>
            <tr style={{ textAlign: "left", borderBottom: "1px solid #f1f5f9" }}>
              <th style={estiloTh}>Fecha</th>
              <th style={estiloTh}>Concepto</th>
              <th style={estiloTh}>Monto</th>
              <th style={estiloTh}>Hora</th>
              <th style={{ ...estiloTh, textAlign: "center" }}>Estado</th>
            </tr>
          </thead>
          <tbody>
            {transacciones.map((t) => (
              <tr key={t.id} style={{ borderBottom: "1px solid #f8fafc" }}>
                <td style={estiloTd}>{t.fecha}</td>
                <td style={estiloTd}>{t.concepto}</td>
                <td style={{ ...estiloTd, fontWeight: "700", color: colores.primario }}>{t.monto}</td>
                <td style={estiloTd}>{t.hora}</td>
                <td style={{ padding: "15px", textAlign: "center" }}>
                  <span style={{ 
                    background: colores.azulBoton, 
                    color: "white", 
                    padding: "8px 20px", 
                    borderRadius: "8px", 
                    fontSize: "13px",
                    fontWeight: "600",
                    display: "inline-block",
                    minWidth: "110px"
                  }}>
                    {t.estado}
                  </span>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}

const estiloTh = { padding: "15px", color: "#0e2a5a", fontSize: "14px", fontWeight: "bold" };
const estiloTd = { padding: "18px 15px", color: "#64748b", fontSize: "14px" };