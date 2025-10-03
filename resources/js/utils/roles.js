export const formatRoleName = (roleName) => {
  const roleNames = {
    'admin': 'Administrador',
    'salesman': 'Vendedor'
  };
  
  return roleNames[roleName] || roleName;
};